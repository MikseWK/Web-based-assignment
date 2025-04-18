<?php
require_once 'base.php';
require_once '../vendor/autoload.php';
$stripe_config = require_once '../config/stripe_config.php';

// Set your Stripe API key
\Stripe\Stripe::setApiKey($stripe_config['secret_key']);

// Webhook secret from Stripe dashboard
$endpoint_secret = $stripe_config['webhook_secret'];

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'checkout.session.completed':
        $session = $event->data->object;
        
        // Extract metadata
        $payment_id = $session->metadata->payment_id;
        $customer_id = $session->metadata->customer_id;
        
        // Update payment status
        $update_payment = "UPDATE payment SET status = 'completed' WHERE id = ?";
        $stmt = $mysqli->prepare($update_payment);
        $stmt->bind_param("i", $payment_id);
        $stmt->execute();
        
        // Get order IDs associated with this payment
        $get_orders = "SELECT order_id FROM payment WHERE id = ?";
        $stmt = $mysqli->prepare($get_orders);
        $stmt->bind_param("i", $payment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $payment_data = $result->fetch_assoc();
        
        if ($payment_data) {
            // Update order status
            $update_order = "UPDATE orders SET status = 'paid' WHERE id = ?";
            $stmt = $mysqli->prepare($update_order);
            $stmt->bind_param("i", $payment_data['order_id']);
            $stmt->execute();
        }
        
        break;
    // Add other event types as needed
    default:
        // Unexpected event type
        http_response_code(400);
        exit();
}

http_response_code(200);