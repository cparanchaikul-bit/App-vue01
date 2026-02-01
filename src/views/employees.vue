<template>
  <div class="container mt-4">
    <h2 class="mb-3">รายชื่อพนักงาน</h2>
    
    <!-- ตารางแสดงข้อมูลลูกค้า -->
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>รหัสพนักงาน</th>
          <th>ชื่อพนักงาน</th>
          <th>แผนก</th>
          <th>เงินเดือน</th>
          <th>สถานะ</th>
          <th>วันที่เริ่มงาน</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="employees in employeess" :key="employees.emp_id">
          <td>{{ employees.emp_id }}</td>
          <td>{{ employees.full_name }}</td>
          <td>{{ employees.department }}</td>
          <td>{{ employees.salary }}</td>
          <td>{{ employees.active }}</td>
          <td>{{ employees.created_at }}</td>

        </tr>
      </tbody>
    </table>

    <div class="mb-3 text-end">
    <a class="btn btn-primary" href="/add_employees" role="button">Add+</a>
    </div>


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
  name: "EmployeesList",
  setup() {
    const employeess = ref([]);
    const loading = ref(true);
    const error = ref(null);

    // ฟังก์ชันดึงข้อมูลจาก API
    const fetchEmployeess = async () => {
      try {
        const response = await fetch("http://localhost/App-vue01/php_api/employees.php");
        if (!response.ok) {
          throw new Error("ไม่สามารถดึงข้อมูลได้");
        }
        employeess.value = await response.json();
      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      fetchEmployeess();
    });

    return {
      employeess,
      loading,
      error
    };
  }
};
</script>
