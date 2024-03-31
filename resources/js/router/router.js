import { createWebHistory, createRouter } from "vue-router";
import Dashboard from "../pages/Dashboard.vue";
import NotFound from "../pages/NotFound.vue";

const routes = [
    {
        path: "/dashboard",
        component: Dashboard,
    },
    {
        path: "/:pathMatch(.*)*",
        name: "not-found",
        component: NotFound,
    },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.APP_URL),
    routes,
    scrollBehavior() {
        window.scrollTo(0, 0);
    },
});

export default router;
