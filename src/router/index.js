import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";

const routes = [
  {
    path: "/",
    name: "home",
    component: HomeView,
  },
  {
    path: "/customer", // ปรับเป็นตัวพิมพ์เล็กตามมาตรฐาน URL
    name: "Customer",
    component: () => import("../views/Customer.vue"),
  },
  {
    path: "/contact",
    name: "Contact", // แก้ไขคำผิดจาก Comtact เป็น Contact
    component: () => import("../views/Contact.vue"),
  },
  {
    path: "/type",
    name: "type",
    component: () => import("../views/type.vue"),
  },
  {
    path: "/employees",
    name: "employees",
    component: () => import("../views/employees.vue"),
  },
  {
    path: "/add_customer",
    name: "add_customer",
    component: () => import("../views/Add_customer.vue"),
  },
  {
    path: "/add_employees",
    name: "add_employees",
    component: () => import("../views/Add_employees.vue"),
  },
  {
    path: "/product",
    name: "product",
    component: () => import("../views/Product.vue"),
  },
  {
    path: "/product_api",
    name: "product_api",
    component: () => import("../views/Product_api.vue"),
  },
  {
    path: "/show_product",
    name: "show_product",
    component: () => import("../views/Show_product.vue"),
  },
  {
    path: "/customer_crud",
    name: "customer_crud",
    component: () => import("../views/Customer_crud.vue"),
  },
  {
    path: "/employee_crud",
    name: "employee_crud",
    
  component: () => import("../views/employee_crud.vue"),
  },
  {
    path: "/type_crud",
    name: "type_crud",
    
  component: () => import("../views/type_crud.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;