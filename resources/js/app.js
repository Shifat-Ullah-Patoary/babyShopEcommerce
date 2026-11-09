const formatCurrency = (value) => `$${value.toFixed(2)}`;

const updateCartTotals = () => {
    let total = 0;

    document.querySelectorAll('.cart-quantity').forEach((input) => {
        const quantity = Math.min(99, Math.max(0, Number.parseInt(input.value, 10) || 0));
        const lineTotal = Number.parseFloat(input.dataset.price) * quantity;
        const lineElement = input.closest('[data-cart-item]')?.querySelector('[data-line-total]');

        input.value = quantity;
        if (lineElement) {
            lineElement.textContent = formatCurrency(lineTotal);
        }
        total += lineTotal;
    });

    const totalElement = document.querySelector('[data-cart-total]');
    const subtotalElement = document.querySelector('[data-cart-subtotal]');
    const deliveryCharge = Number.parseFloat(totalElement?.dataset.deliveryCharge || '0');

    if (subtotalElement) {
        subtotalElement.textContent = formatCurrency(total);
    }

    if (totalElement) {
        totalElement.textContent = formatCurrency(total + deliveryCharge);
    }
};

document.addEventListener('input', (event) => {
    if (event.target.matches('.cart-quantity')) {
        updateCartTotals();
    }
});

document.querySelector('[data-close-order-modal]')?.addEventListener('click', () => {
    const modal = document.querySelector('#order-success-modal');
    modal?.remove();
    document.querySelector('#checkout-form input')?.focus();
});

const removeConfirmation = document.querySelector('#remove-cart-confirmation');
let removeButton = null;

document.querySelectorAll('[data-remove-cart-item]').forEach((button) => {
    button.addEventListener('click', (event) => {
        event.preventDefault();
        removeButton = button;
        removeConfirmation?.classList.replace('hidden', 'flex');
    });
});

document.querySelector('[data-cancel-remove]')?.addEventListener('click', () => {
    removeConfirmation?.classList.replace('flex', 'hidden');
    removeButton = null;
});

document.querySelector('[data-confirm-remove]')?.addEventListener('click', () => {
    const form = removeButton?.closest('form');

    if (form) {
        form.requestSubmit(removeButton);
    }
});
