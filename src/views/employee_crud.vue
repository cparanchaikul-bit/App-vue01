<template>
  <div class="container mt-4">
    <h2 class="mb-3">รายชื่อลูกค้า</h2>
    
    <div class="mb-3">
      <button class="btn btn-primary" @click="openAddModal">
        Add <i class="bi bi-plus-circle"></i>
      </button>
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>ชื่อ</th>
          <th>แผนก</th>
          <th>เงินเดือน</th>
          <th>สถานะ</th>
          <th>แก้ไข</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="employees in employees" :key="employees.emp_id">
          <td>{{ employees.emp_id }}</td>
          <td>{{ employees.full_name }}</td>
          <td>{{ employees.department }}</td>
          <td>{{ employees.salary }}</td>
          <td>{{ employees.active }}</td>
          <td>
            <button class="btn btn-warning btn-sm" @click="openEditModal(employees)">
              แก้ไข
            </button>
            |
            <button class="btn btn-danger btn-sm" @click="deleteemployees(employees.emp_id)">
              ลบ
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="loading" class="text-center"><p>กำลังโหลดข้อมูล...</p></div>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <!-- ✅ Modal ใช้ทั้งเพิ่ม/แก้ไข -->
    <div class="modal fade" id="editModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEditMode ? "แก้ไขข้อมูลพนักงาน" : "เพิ่มพนักงานใหม่" }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveEmployees">
              <div class="mb-3">
                <label class="form-label">ชื่อ</label>
                <input v-model="editEmployees.full_name" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">แผนก</label>
                <input v-model="editEmployees.department" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">เงินเดือน</label>
                <input v-model="editEmployees.salary" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">สถานะ</label>
                <input v-model="editEmployees.active" type="text" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-success">
                {{ isEditMode ? "บันทึกการแก้ไข" : "เพิ่มลูกค้า" }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { ref, onMounted } from "vue";

export default {
  name: "EmployeeList",
  setup() {
    const employees = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const editEmployees = ref({});
    const isEditMode = ref(false);
    let editModal = null;

    const fetchemployees = async () => {
      try {
        const response = await fetch("http://localhost/app-vue01/php_api/employee_crud.php");
        const result = await response.json();

        if (result.success) {
          employees.value = result.data;
        } else {
          error.value = result.message;
        }
      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      fetchemployees();
      const modalEl = document.getElementById("editModal");
      editModal = new window.bootstrap.Modal(modalEl);
    });

    // ✅ เปิด Modal เพิ่มลูกค้าใหม่
    const openAddModal = () => {
      isEditMode.value = false;
      editEmployees.value = {
        full_name: "",
        department: "",
        salary: "",
        active: "1",
      };
      editModal.show();
    };

    // ✅ เปิด Modal แก้ไขลูกค้า
    const openEditModal = (employees) => {
      isEditMode.value = true;
      editEmployees.value = { ...employees, password: "" };
      editModal.show();
    };

    // ✅ ใช้ฟังก์ชันเดียวสำหรับทั้งเพิ่ม/แก้ไข
    const saveEmployees = async () => {
      const url = "http://localhost/App-vue01/php_api/employee_crud.php";
      const method = isEditMode.value ? "PUT" : "POST";

      try {
        const response = await fetch(url, {
          method,
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(editEmployees.value)
        });

        const result = await response.json();

        if (result.success) {
          alert(result.message);
          fetchemployees();
          editModal.hide();
        } else {
          alert(result.message);
        }
      } catch (err) {
        alert("เกิดข้อผิดพลาด: " + err.message);
      }
    };

    // ✅ ลบลูกค้า
    const deleteemployees = async (id) => {
      if (!confirm("คุณต้องการลบข้อมูลนี้ใช่หรือไม่?")) return;
      try {
        const response = await fetch("http://localhost/App-vue01/php_api/employee_crud.php", {
          method: "DELETE",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ emp_id: id })
        });
        const result = await response.json();
        if (result.success) {
          employees.value = employees.value.filter(c => c.emp_id !== id);
          alert(result.message);
        } else {
          alert(result.message);
        }
      } catch (err) {
        alert("เกิดข้อผิดพลาด: " + err.message);
      }
    };

    return {
      employees,
      loading,
      error,
      editEmployees,
      isEditMode,
      openAddModal,
      openEditModal,
      saveEmployees,
      deleteemployees
    };
  }
};
</script>