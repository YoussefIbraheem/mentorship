from abc import ABC, abstractmethod
from dataclasses import dataclass


# ---- Your application's contract ----
@dataclass(frozen=True)
class PaymentResult:
    success: bool
    transaction_id: str | None = None
    error: str | None = None


class PaymentProcessor(ABC):
    @abstractmethod
    def process_payment(self, amount: float) -> PaymentResult:
        """Charge `amount` in US dollars.
        Business failures (declined, invalid amount, not completed) must be
        returned as PaymentResult(success=False, error=...), never raised."""
        ...


# ---- Existing gateway ----
class PayPalProcessor(PaymentProcessor):
    def process_payment(self, amount: float) -> PaymentResult:
        if amount <= 0:
            return PaymentResult(False, error="Amount must be positive")
        return PaymentResult(True, transaction_id="PAYPAL-TXN-001")


# ---- Third-party Stripe SDK (you cannot change it) ----
class StripeError(Exception): ...


class CardDeclinedError(StripeError): ...


class InvalidRequestError(StripeError): ...


class StripeSDK:
    def __init__(self):
        self._counter = 0

    def make_charge(self, value_in_cents: int, currency: str = "usd") -> dict:
        if not isinstance(value_in_cents, int):
            raise TypeError("value_in_cents must be an int")
        if value_in_cents <= 0:
            raise InvalidRequestError("Amount must be at least 1 cent")
        if value_in_cents == 9999:  # simulated declined card
            raise CardDeclinedError("Your card was declined")
        self._counter += 1
        status = "pending" if value_in_cents >= 1_000_000 else "succeeded"
        return {
            "id": f"ch_{self._counter:04d}",
            "status": status,  # "succeeded" or "pending"
            "amount": value_in_cents,
            "currency": currency,
        }


class StripePaymentProcessorAdapter(PaymentProcessor):
    def __init__(self, sdk: StripeSDK):
        self._sdk = sdk

    def process_payment(self, amount: float) -> PaymentResult:
        stripe_res = {}
        try:
            amount_in_cents = round(amount * 100)
            stripe_res = self._sdk.make_charge(amount_in_cents)

        except StripeError as e:
            stripe_res["error"] = str(e)
        except (TypeError, ValueError, OverflowError) as e:
            stripe_res["error"] = f"Unexpected error: {e}"
        if stripe_res.get("status") == "pending":
            stripe_res["error"] = stripe_res.get(
                "error", "Payment is pending, please wait for confirmation"
            )

        result = PaymentResult(
            success=stripe_res.get("status") == "succeeded",
            error=stripe_res.get("error", None),
            transaction_id=stripe_res.get("id", None),
        )

        return result



# ---- Existing client code (do not modify) ----
def checkout(processor: PaymentProcessor, amount: float) -> str:
    result = processor.process_payment(amount)
    if result.success:
        return f"Order confirmed (txn {result.transaction_id})"
    return f"Payment failed: {result.error}"
