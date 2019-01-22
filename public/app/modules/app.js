const NotFound = { template: '<p>Not found</p>' };

const routes = {
    '/': MainViewer,
};

new Vue ({
    el: '#app',
    data: {
        currentRoute: window.location.pathname
    },
    computed: {
        ViewComponent () {
            return routes [this.currentRoute] || NotFound
        }
    },
    render (h) { return h (this.ViewComponent) }
});
