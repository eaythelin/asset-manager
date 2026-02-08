import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js', 
                'resources/js/category/delete-category.js',
                'resources/js/category/edit-category.js',
                'resources/js/dashboard/categorypiechart.js',
                'resources/js/dashboard/subcategory.js',
                'resources/js/department/delete-department.js',
                'resources/js/department/edit-department.js',
                'resources/js/subcategory/delete-subcategory.js',
                'resources/js/subcategory/edit-subcategory.js',
                'resources/js/supplier/delete-supplier.js',
                'resources/js/supplier/edit-supplier.js',
                'resources/js/user/soft-delete-user.js',
                'resources/js/user/edit-user.js',
                'resources/js/user/restore-user.js',
                'resources/js/user/force-delete-user.js',
                'resources/js/employee/delete-employee.js',
                'resources/js/employee/edit-employee.js',
                'resources/js/assets/dispose-asset/disposeAsset.js',
                'resources/js/assets/edit-asset/getEditSubcategory.js',
                'resources/js/assets/endOfLifeCalc.js',
                'resources/js/assets/create-asset/getSubcategory.js',
                'resources/js/requests/create-requests/getSubcategories.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
