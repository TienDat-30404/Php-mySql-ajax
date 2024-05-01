const container = document.querySelector('.banner')
setInterval(
    () => {
        const last = container.firstElementChild;
        last.remove();
        container.appendChild(last);
    },
    3000
)





