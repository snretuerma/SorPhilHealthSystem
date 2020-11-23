<template>
    <el-dialog
        title="Doctor Details"
        :visible="openDialog"
        top="5vh"
        center
        :close-on-press-escape="false"
        :close-on-click-modal="false"
    >
        <el-form :model="form" :rules="rules" ref="form">
            <el-form-item
                label="Name"
                label-width="150px"
                prop="name"
            >
                <el-input
                    v-model="form.name"
                    autocomplete="off"
                ></el-input>
                <span
                    class="font-italic text-danger"
                    v-if="errors.name"
                    ><small>{{ errors.name[0] }}</small></span
                >
            </el-form-item>
            <el-form-item
                label="Employment Type"
                label-width="150px"
                prop="is_parttime"
            >
                <el-radio-group v-model="form.is_parttime">
                    <el-radio label="1">Full-time</el-radio>
                    <el-radio label="0">Part-time</el-radio>
                </el-radio-group>
                <br />
                <span
                    class="font-italic text-danger"
                    v-if="errors.is_parttime"
                    ><small>{{ errors.is_parttime[0] }}</small></span
                >
            </el-form-item>
        </el-form>

        <span slot="footer" class="dialog-footer">
            <el-button @click="dialogFormVisible = false">Cancel</el-button>
            <el-button
                v-if="form.formmode == 'add'"
                type="primary"
                @click="
                    personnelFunctions('add');
                    formLoading();
                "
                >Save</el-button
            >
            <el-button
                v-if="form.formmode == 'edit'"
                type="primary"
                @click="
                    personnelFunctions('edit');
                    formLoading();
            ">
                Save Changes
            </el-button
            >
        </span>
    </el-dialog>
</template>

<script>
export default {
    name: 'AddEditDoctorDialog',
    props: ['rowData', 'openDialog', 'formType'],
    computed: {
        openDialog: {
            set() {
                return false;
            },
            get() {

            }
        }
    },
    data() {
        return {
            dialog_open: false,
            errors: [],
            form: {
                id: "",
                name: "",
                is_parttime: "",
            },
            rules: {
                name: [
                    {
                        required: true,
                        message: "Lastname is required.",
                        trigger: "blur",
                    },
                ],
                is_parttime: [
                    {
                        required: true,
                        message: "Please select employment type.",
                        trigger: "change",
                    },
                ],
            },
        }
    },
    mounted() {

    }
}
</script>