from payment_processor import (
    PayPalProcessor,
    StripePaymentProcessorAdapter,
    StripeSDK,
    checkout,
)

stripe = StripePaymentProcessorAdapter(StripeSDK())

print(checkout(PayPalProcessor(), 25.50))  # sanity check
print(checkout(stripe, 25.50))
print(checkout(stripe, 19.99))
print(checkout(stripe, 0.10))
print(checkout(stripe, 99.99))
print(checkout(stripe, 0))
print(checkout(stripe, -5.00))
print(checkout(stripe, 10000.00))
