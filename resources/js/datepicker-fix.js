/**
 * Datepicker Positioning Fix
 * Fixes positioning issues when datepicker is inside modals or scrollable containers
 */

document.addEventListener('alpine:init', () => {
    // Override the default datepicker positioning behavior
    Alpine.directive('datepicker-position', (el, { expression, modifiers }, { effect, evaluateLater }) => {
        const trigger = el.querySelector('[x-ref="trigger"]');
        const popover = el.querySelector('.silatar-datepicker-popover');

        if (!trigger || !popover) return;

        const updatePosition = () => {
            const triggerRect = trigger.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const viewportWidth = window.innerWidth;

            // Calculate available space
            const spaceBelow = viewportHeight - triggerRect.bottom;
            const spaceAbove = triggerRect.top;
            const spaceRight = viewportWidth - triggerRect.left;
            const spaceLeft = triggerRect.left;

            // Popover dimensions (approximate)
            const popoverHeight = 380;
            const popoverWidth = 336;

            // Determine vertical position
            let top, position;
            if (spaceBelow >= popoverHeight + 12) {
                // Space below is enough
                top = triggerRect.bottom + window.scrollY + 12;
                position = 'bottom';
            } else if (spaceAbove >= popoverHeight + 12) {
                // Space above is enough
                top = triggerRect.top + window.scrollY - popoverHeight - 12;
                position = 'top';
            } else {
                // Not enough space, default to below but adjust
                top = triggerRect.bottom + window.scrollY + 12;
                position = 'bottom';
            }

            // Determine horizontal position
            let left;
            if (spaceRight >= popoverWidth) {
                left = triggerRect.left + window.scrollX;
            } else if (spaceLeft >= popoverWidth) {
                left = triggerRect.right + window.scrollX - popoverWidth;
            } else {
                // Default to left edge
                left = triggerRect.left + window.scrollX;
            }

            // Apply styles
            popover.style.position = 'absolute';
            popover.style.top = `${top}px`;
            popover.style.left = `${left}px`;
            popover.style.right = 'auto';
            popover.style.bottom = 'auto';
            popover.dataset.position = position;
        };

        // Update position when popover opens
        effect(() => {
            const isOpen = el.__x && el.__x.$data && el.__x.$data.open;
            if (isOpen) {
                // Small delay to ensure DOM is updated
                setTimeout(updatePosition, 10);
            }
        });

        // Update position on scroll
        const scrollContainer = el.closest('.modal-body, .page-content, [x-scroll]');
        if (scrollContainer) {
            scrollContainer.addEventListener('scroll', () => {
                const isOpen = el.__x && el.__x.$data && el.__x.$data.open;
                if (isOpen) {
                    updatePosition();
                }
            }, { passive: true });
        }

        // Update position on window resize
        window.addEventListener('resize', () => {
            const isOpen = el.__x && el.__x.$data && el.__x.$data.open;
            if (isOpen) {
                updatePosition();
            }
        }, { passive: true });
    });
});

/**
 * Fix datepicker in modals
 * Re-initialize datepickers when modal opens
 */
function fixDatepickersInModals() {
    // Listen for modal open events
    document.addEventListener('modal-open', (event) => {
        const modal = event.detail.modal || event.target;

        // Find all datepickers in the modal
        const datepickers = modal.querySelectorAll('.silatar-datepicker');

        datepickers.forEach((datepicker) => {
            // Re-initialize if needed
            if (datepicker.__x) {
                // Alpine component exists, might need to refresh
                const popover = datepicker.querySelector('.silatar-datepicker-popover');
                if (popover) {
                    // Ensure popover is properly positioned
                    popover.style.position = 'fixed';
                    popover.style.zIndex = '9999';
                }
            }
        });
    });
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    fixDatepickersInModals();

    // Also fix for dynamically added datepickers
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === 1) {
                    const datepickers = node.querySelectorAll
                        ? node.querySelectorAll('.silatar-datepicker')
                        : [];

                    datepickers.forEach((datepicker) => {
                        // Ensure popover has fixed positioning for modals
                        const popover = datepicker.querySelector('.silatar-datepicker-popover');
                        if (popover) {
                            const isInModal = datepicker.closest('.modal, [role="dialog"]');
                            if (isInModal) {
                                popover.style.position = 'fixed';
                                popover.style.zIndex = '9999';
                            }
                        }
                    });
                }
            });
        });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});
