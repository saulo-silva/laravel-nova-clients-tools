Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'clients',
            path: '/clients',
            component: require('./components/Tool'),
        },
    ])
})
