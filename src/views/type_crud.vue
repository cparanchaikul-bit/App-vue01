<template>
  <div class="container mt-4">
    <h2 class="mb-3">รายชื่อประเภทสินค้า</h2>
    
    <div class="mb-3">
      <button class="btn btn-primary" @click="openAddModal">
        Add <i class="bi bi-plus-circle"></i>
      </button>
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>ชื่อประเภท</th>
          <th>แก้ไข</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="type in types" :key="type.type_id">
          <td>{{ type.type_id }}</td>
          <td>{{ type.type_name }}</td>
          <td>
            <button class="btn btn-warning btn-sm" @click="openEditModal(type)">
              แก้ไข
            </button>
            |
            <button class="btn btn-danger btn-sm" @click="deletetypes(type.type_id)">
              ลบ
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="loading" class="text-center"><p>กำลังโหลดข้อมูล...</p></div>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <div class="modal fade" id="editModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEditMode ? "แก้ไขข้อมูล" : "เพิ่มข้อมูล" }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="savetypes">
              <div class="mb-3">
                <label class="form-label">ชื่อประเภทสินค้า</label>
                <input v-model="edittypes.type_name" type="text" class="form-control" required>
              </div>
            
              <button type="submit" class="btn btn-success">
                {{ isEditMode ? "บันทึกการแก้ไข" : "เพิ่มข้อมูล" }}
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
  name: "typeList",
  setup() {
    const types = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const edittypes = ref({ type_name: "" }); // กำหนดค่าเริ่มต้น
    const isEditMode = ref(false);
    let modalInstance = null;

    const fetchtypes = async () => {
      try {
        const response = await fetch("http://localhost/app-vue01/php_api/type_crud.php");
        const result = await response.json();
        if (result.success) {
          types.value = result.data;
        } else {
          error.value = result.message;
        }
      } catch (err) {
        error.value = "เชื่อมต่อ API ไม่ได้: " + err.message;
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      fetchtypes();
      const modalEl = document.getElementById("editModal");
      if (modalEl) {
        modalInstance = new window.bootstrap.Modal(modalEl);
      }
    });

    const openAddModal = () => {
      isEditMode.value = false;
      edittypes.value = { type_name: "" };
      modalInstance.show();
    };

    const openEditModal = (item) => {
      isEditMode.value = true;
      edittypes.value = { ...item }; // คัดลอกข้อมูลใส่ Modal
      modalInstance.show();
    };

    const savetypes = async () => {
      const url = "http://localhost/app-vue01/php_api/type_crud.php";
      const method = isEditMode.value ? "PUT" : "POST";

      try {
        const response = await fetch(url, {
          method,
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(edittypes.value)
        });

        const result = await response.json();
        if (result.success) {
          alert(result.message);
          fetchtypes();
          modalInstance.hide();
        } else {
          alert(result.message);
        }
      } catch (err) {
        alert("เกิดข้อผิดพลาด: " + err.message);
      }
    };

    const deletetypes = async (id) => {
      if (!confirm("คุณต้องการลบข้อมูลนี้ใช่หรือไม่?")) return;
      try {
        const response = await fetch("http://localhost/app-vue01/php_api/type_crud.php", {
          method: "DELETE",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ type_id: id }) // เปลี่ยนจาก emp_id เป็น type_id ให้ตรงกับ API
        });
        const result = await response.json();
        if (result.success) {
          alert(result.message);
          fetchtypes();
        } else {
          alert(result.message);
        }
      } catch (err) {
        alert("เกิดข้อผิดพลาด: " + err.message);
      }
    };

    return {
      types,
      loading,
      error,
      edittypes,
      isEditMode,
      openAddModal,
      openEditModal,
      savetypes,
      deletetypes
    };
  }
};
</script>