<template>
  <div class="container mt-4">
    <h2 class="mb-3">รายการประเภทสินค้า</h2>
    
    <!-- ตารางแสดงข้อมูลลูกค้า -->
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
         <tr>
          
          <th>รหัสพนักงาน</th>
          <th>ชื่อ-นามสกุล</th>
          <th>ตำแหน่ง</th>
          <th>เงินเดือน</th>
          <th>สถานะ</th>
          <th>วันที่สร้าง</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="employee in employee" :key="employee.emp_id">
          <td>{{ employee.emp_id }}</td>
          <td>{{ employee.full_name }}</td>
           <td>{{ employee.department }}</td>
          <td>{{ employee.salary }}</td>
          <td>{{ employee.active }}</td>
          <td>{{ employee.created_at }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Loading -->
    <div v-if="loading" class="text-center">
      <p>กำลังโหลดข้อมูล...</p>
    </div>

    <!-- Error -->
    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";

export default {
  name: "employeeList",
  setup() {
    const employee = ref([]);
    const loading = ref(true);
    const error = ref(null);

    // ฟังก์ชันดึงข้อมูลจาก API
    const fetchemployee = async () => {
      try {
        const response = await fetch("http://localhost/App-vue01/php_api/show_employee.php");
        if (!response.ok) {
          throw new Error("ไม่สามารถดึงข้อมูลได้");
        }
        employee.value = await response.json();
      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      fetchemployee();
    });

    return {
      employee, employee,
      loading,
      error
    };
  }
};
</script>
