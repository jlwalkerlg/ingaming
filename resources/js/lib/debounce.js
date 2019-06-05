// Debounce event handlers.
// Ensures event handler only fires after a certain
// amount of time has passed since the last event fired.
export function debounce(handler, delay, ...args) {
    let timeout;
    let context;
    let event;
    const callback = () => {
        handler.call(context, event, ...args);
    };
    return function(e) {
        event = e;
        if (!context) context = this;
        if (timeout) {
            clearTimeout(timeout);
            timeout = null;
        }
        timeout = setTimeout(callback, delay);
    }
}
