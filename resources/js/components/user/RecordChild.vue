<template>
    <div>
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                <i class="fa fa-file-medical-alt"></i>&nbsp;&nbsp;Records
                </span>
            </div>
        </div>
        <div class="row" id="search_box" style="margin-bottom: 10px">
            <div class="col-xl-8 col-lg-7 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
                        <el-input
                            prefix-icon="el-icon-search"
                            v-model="search"
                            placeholder="Type to search"
                        />
                    </div>
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
                        <el-form ref="form">
                            <el-form-item prop="batch">
                                    <el-select
                                        ref="defaultValue"
                                        :required="true"
                                        v-model="value"
                                        class="block-button"
                                        size="large"
                                        value-key="batch"
                                        multiple
                                        :multiple-limit="1"
                                        filterable
                                        default-first-option
                                        @change="changes">
                                        <el-option
                                            v-for="item in batch"
                                            :key="item.batch"
                                            :label="item.label"
                                            :value="item.batch"
                                            >
                                        {{item.batch}}</el-option>
                                    </el-select>
                            </el-form-item>
                        </el-form>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
                        <!-- <el-button
                            class="btn-action block-button"
                            @click="triggerAdd"
                        >
                            Add
                        </el-button> -->
                        <el-dropdown @command="formDialog" class="block-button btn-action">
                            <el-button>
                                Actions
                                <i class="el-icon-arrow-down el-icon--right"/>
                            </el-button>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item
                                    icon="el-icon-document-add"
                                    command="add_record"
                                    >
                                    Add Record
                                </el-dropdown-item>
                                <el-dropdown-item
                                    icon="el-icon-delete"
                                    command="delete_batch"
                                    >
                                    Delete by batch
                                </el-dropdown-item
                                >
                            </el-dropdown-menu>
                        </el-dropdown>
                    </div>
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
                        <el-dropdown @command="formDialog" class="block-button btn-action">
                            <el-button>
                                Excel
                                <i class="el-icon-arrow-down el-icon--right"/>
                            </el-button>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item
                                    icon="el-icon-upload2"
                                    command="import_data"
                                    >
                                    Upload Data
                                </el-dropdown-item>
                                <el-dropdown-item
                                    icon="el-icon-download"
                                    command="export_data"
                                    >
                                    Download ...
                                </el-dropdown-item
                                >
                            </el-dropdown-menu>
                        </el-dropdown>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div id="test">
                            <el-table v-loading="loading" :data="listData">
                                <el-table-column
                                    width="200"
                                    label="Patient"
                                    prop="patient_name"
                                >
                                </el-table-column>
                                <el-table-column
                                    width="177"
                                    label="Admission Date"
                                    prop="admission_date"
                                    column-key="ase"
                                >
                                </el-table-column>
                                <el-table-column
                                    width="177"
                                    label="Discharge Date"
                                    prop="discharge_date"
                                >
                                </el-table-column>
                                <el-table-column
                                    width="110"
                                    label="PF"
                                    prop="total"
                                >
                                </el-table-column>
                                <el-table-column
                                    width="140"
                                    label="Attending"
                                    prop="allattending"
                                >
                                </el-table-column>
                                <el-table-column
                                    width="115"
                                    label="Requesting"
                                    prop="allrequesting"
                                >
                                </el-table-column>
                                <el-table-column
                                    min-width="115"
                                    label="Surgeon"
                                    prop="allsurgeon"
                                >
                                </el-table-column>
                                <el-table-column
                                    min-width="115"
                                    label="Healthcare"
                                    prop="allhealthcare"
                                >
                                </el-table-column>
                                <el-table-column
                                    min-width="115"
                                    label="ER"
                                    prop="aller"
                                >
                                </el-table-column>
                                <el-table-column
                                    min-width="130"
                                    label="Anesthesiologist"
                                    prop="allanesthesiologist"
                                >
                                </el-table-column>
                                <el-table-column
                                    min-width="130"
                                    label="Co-management"
                                    prop="allcomanagement"
                                >
                                </el-table-column>
                                <el-table-column
                                    min-width="115"
                                    label="Admitting"
                                    prop="alladmitting"
                                >
                                </el-table-column>
                                <el-table-column
                                    width="100"
                                    align="center"
                                    fixed="right"
                                >
                                    <template slot="header">
                                        Action
                                    </template>
                                    <template slot-scope="scope">
                                        <el-tooltip
                                            class="item"
                                            effect="light"
                                            content="Edit"
                                            placement="top"
                                            :enterable = false
                                        >
                                            <el-button
                                                size="mini"
                                                type="primary"
                                                icon="el-icon-edit"
                                                @click="handleEditRecord(scope.$index, scope.row)"
                                                circle
                                            >
                                            </el-button>
                                        </el-tooltip>
                                        <el-tooltip
                                            class="item"
                                            effect="light"
                                            content="delete"
                                            placement="top"
                                            :enterable = false
                                        >
                                            <el-button
                                                size="mini"
                                                type="danger"
                                                icon="el-icon-delete"
                                                @click="handleDeleteRecord(scope.$index, scope.row)"
                                                circle
                                            ></el-button>
                                        </el-tooltip>
                                    </template>
                                </el-table-column>
                            </el-table>
                        <div align="center">
                            <el-pagination
                                background
                                layout="prev, pager, next"
                                @current-change="handleCurrentChange"
                                :page-size="pageSize"
                                :total="total"
                        >
                    </el-pagination>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <el-dialog :title="dialogtitle" :visible.sync="dialogExcelFile" :fullscreen="fullscreen">
            <el-row v-show="!isimport">
                <el-col>
                    <el-form>
                        <el-form-item>
                            <el-button @click="getTemplate">Download Sample Excel File / Template</el-button>
                            <el-button type="primary" @click="exportExcel">Download Data</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-row>
            <el-row v-show="isimport">
                <el-form>
                    <el-form-item>
                        <el-upload
                        class="upload-demo"
                        ref="upload"
                        action="import_doctor_record"
                        :auto-upload="false"
                        :on-change="fileData"
                        :limit="1"
                        :on-exceed="handleExceedFile"
                        :on-remove="handleRemoveFile"
                        accept=".xlsx"
                        >
                            <el-button slot="trigger" type="primary" plain>select supported excel file .xlsx</el-button>
                            <el-button type="success" @click="uploadToDatabase" :disabled="!is_preview">upload to database</el-button>
                        </el-upload>
                    </el-form-item>
                </el-form>
                <el-row v-show="is_preview && preview_excel_sheet_data.length > 0">
                    <el-col>
                        <el-tabs type="border-card" tab-position="bottom" @tab-click="handleClickTab">
                            <el-tab-pane
                                v-for="(item,index) in preview_excel_sheet_data"
                                :key="item.name"
                                :label="item.title"
                            >
                            {{ item.title }}
                                <el-table
                                    :data="exceldata"
                                    style="width: 100%"
                                    border
                                    >
                                    <el-table-column
                                    fixed
                                    prop="Patient_Name"
                                    label="Patient Name"
                                >
                                    </el-table-column>
                                    <el-table-column
                                    prop="Admission_Date"
                                    label="Admission Date"
                                    :formatter="covertDate"
                                >
                                    </el-table-column>
                                    <el-table-column
                                    prop="Discharge_Date"
                                    label="Discharge Date"
                                    :formatter="covertDate"
                                    >
                                    </el-table-column>
                                    <el-table-column
                                    prop="Total_PF"
                                    label="Total PF"
                                >
                                    </el-table-column>
                                    <el-table-column
                                    prop="Attending_Physician"
                                    label="Attending Physician"
                                    >
                                    </el-table-column>
                                    <el-table-column
                                    prop="Admitting_Physician"
                                    label="Admitting Physician"
                                    >
                                    </el-table-column>
                                    <el-table-column
                                    prop="Requesting_Physician"
                                    label="Requesting Physician"
                                >
                                    </el-table-column>
                                    <el-table-column
                                    prop="Reffered_Physician"
                                    label="Reffered Physician"
                                    >
                                    </el-table-column>
                                    <el-table-column
                                    prop="Co_Management"
                                    label="Co Management"
                                >
                                    </el-table-column>
                                    <el-table-column
                                    prop="Anesthesiology_Physician"
                                    label="Anesthesiology Physician"
                                    >
                                    </el-table-column>
                                    <el-table-column
                                    prop="Surgeon_Physician"
                                    label="Surgeon Physician"
                                    >
                                    </el-table-column>
                                    <el-table-column
                                    prop="HealthCare_Physician"
                                    label="HealthCare Physician"
                                    >
                                    </el-table-column>
                                    <el-table-column
                                    prop="ER_Physician"
                                    label="ER Physician"
                                    >
                                    </el-table-column>
                                    <el-table-column
                                    prop="Is_Private"
                                    label="Is Private"
                                    min-width="120"
                                    :formatter="covertReadable">
                                    </el-table-column>
                                </el-table>
                                <el-pagination
                                    class="align-middle"
                                    background
                                    layout="prev, pager, next"
                                    @current-change="handleCurrentChangeTable"
                                    :page-size="page_size"
                                    :total="tablelength"
                                />
                            </el-tab-pane>
                        </el-tabs>
                    </el-col>
                </el-row>
                <div v-show="!is_preview && preview_excel_sheet_data.length > 0">
                    <el-alert
                        title='ERROR FOUND, Please see error log bellow'
                        type="error"
                        :closable="false"
                        show-icon>
                    </el-alert>
                    <ul>
                        <dl>
                            <dt>WORKSHEET NAME, format( MonthName Day Year - MonthName Day Year )</dt>
                                <ol>
                                    <li v-for="item in excel_validation_error[0]" :key="item.id">
                                        <strong>{{item.value}}</strong>
                                                {{item.message}}
                                        <strong>[{{item.cell_position}}]</strong>
                                    </li>
                                </ol>
                            <dt>HEADER</dt>
                                <ol>
                                    <li v-for="item in excel_validation_error[1]" :key="item.id">
                                        <strong>{{item.value}}</strong>
                                                {{item.message}}
                                        <strong>[{{item.cell_position}}]</strong>
                                    </li>
                                </ol>
                            <dt>CONTENT</dt>
                                <ol>
                                    <li v-for="item in excel_validation_error[2]" :key="item.id">
                                        <strong>{{item.value}}</strong>
                                                {{item.message}}
                                        <strong>[{{item.cell_position}}]</strong>
                                    </li>
                                </ol>
                        </dl>
                    </ul>
                </div>
            </el-row>
        </el-dialog>
    </div>
</template>
<script>
import XLSX from 'xlsx';
export default {
    data() {
        return {
            search: "",
            data:[],
            batch:[],
            page: 1,
            value:[],
            first_batch:"",
            pageSize: 10,
            total:"",
            dialogExcelFile: false,
            preview_excel_sheet_data:[],
            sheet_length: '',
            tablepage: 1,
            page_size: 10,
            tablelength: 0,
            current_tab_content:[],
            exceldata:[],
            current_tab: '',
            excel_validation_error:[[], [], []],
            import_batch:[],
            doctor_list_compress:[],
            doctor_list_complete:[],
            doctor_export:[],
            is_preview: false,
            fullscreen: true,
            dialogtitle: 'Import Excel',
            isimport: true,
            export_excel:[],
            loading:true
        };
    },
    computed: {
        searching() {
            if (!this.search) {
                this.total = this.data.length;
                return this.data;
            }
            this.page = 1;
            return this.data.filter(
                data =>
                    data.patient_name
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.admission_date
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.discharge_date
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
            );
        },
        listData() {
            this.total = this.searching.length;
            return this.searching.slice(
                this.pageSize * this.page - this.pageSize,
                this.pageSize * this.page
            );
        }
    },
    methods: {
        triggerAdd() {
            this.$emit("add-open", "", "add");
        },
        handleDeleteRecord(index, row) {
            var data = this.data;
            this.deleteRecord(row.id, res_value => {
                if (res_value) {
                    data.splice(data.indexOf(row), 1);
                }
            });
        },
        handleEditRecord(index, row) {
            console.log(row);
            this.$emit("add-open", row, "edit");
        },
        deleteRecord: function(id, res) {
            this.$confirm("Are you sure you want to delete?", "Confirm Delete", {
                    distinguishCancelAndClose: true,
                    confirmButtonText: "Delete",
                    cancelButtonText: "Cancel",
                    type: "warning"
                }
            ).then(() => {
                    var _this = this;
                    axios.delete(`delete_record/${id}`).then(function(response) {
                        if (response.status > 199 && response.status < 203) {
                            _this.$notify({
                                type: 'success',
                                title: 'Delete',
                                message: "Successfully deleted",
                            });
                            _this.getBatch();
                            res(id);
                        }
                    });
                })
                .catch(action => {
                    this.open_notif("info", "Cancelled", "No Changes");
                });
        },
        handleCurrentChange(val) {
            this.page = val;
        },
        changes() {
            if(this.value != '') {
                this.loading = true;
                this.getRecords(this.value);
            }
        },
        getBatch() {
             axios
                .get("get_batch")
                .then(response => {
                    response.data.push({batch:'All'});
                    this.batch = response.data;
                    this.value[0] = response.data[0].batch;
                    this.first_batch = response.data[0].batch;
                    this.getRecords(response.data[0].batch);
                })
                .catch(function(error) {});
        },
        getRecords(batch) {
             axios
                .get("get_records/" + batch)
                .then(response => {
                    response.data.forEach(record => {
                        record.allattending = "";
                        record.allrequesting = "";
                        record.allsurgeon = "";
                        record.allhealthcare = "";
                        record.aller = "";
                        record.allanesthesiologist = "";
                        record.allcomanagement = "";
                        record.alladmitting = "";
                        record.doctors.forEach(doctor => {
                            if (doctor.pivot.doctor_role == "attending") {
                                record.allattending += doctor.name+"; ";
                            }
                            if (doctor.pivot.doctor_role == "requesting") {
                                record.allrequesting += doctor.name+"; ";
                            }
                            if (doctor.pivot.doctor_role == "surgeon") {
                                record.allsurgeon += doctor.name+"; ";
                            }
                            if (doctor.pivot.doctor_role == "healthcare") {
                                record.allhealthcare += doctor.name+"; ";
                            }
                            if (doctor.pivot.doctor_role == "er") {
                                record.aller += doctor.name+"; ";
                            }
                            if (doctor.pivot.doctor_role == "anesthesiologist") {
                                record.allanesthesiologist+=doctor.name+"; ";
                            }
                            if (doctor.pivot.doctor_role == "comanagement") {
                                record.allcomanagement += doctor.name+"; ";
                            }
                            if (doctor.pivot.doctor_role == "admitting") {
                                record.alladmitting += doctor.name+"; ";
                            }
                        });
                    });
                    this.data = response.data;
                    this.loading=false;
                })
                .catch(function(error) {});
        },
        covertDate(row, column, cellValue, index) {
            var hours = cellValue.getHours();
            var minutes = cellValue.getMinutes();
            var ampm = hours >= 12 ? 'pm' : 'am';
            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0'+minutes : minutes;
            var strTime = hours + ampm;
            return cellValue.getFullYear() + "-" + (cellValue.getMonth() + 1) + "-" + cellValue.getDate() + " " + strTime;
        },
        covertReadable(row, column, cellValue, index) {
            if(cellValue == 1){
                return 'Yes';
            }else if(cellValue == 0){
                return 'No';
            }else{
                return 'Not specified';
            }
        },
        handleCurrentChangeTable(value) {
            this.tablepage = value;
            this.tablelength = this.current_tab_content[this.current_tab].length;
            this.exceldata = this.current_tab_content[this.current_tab];
            this.exceldata = this.exceldata.slice(this.page_size * this.tablepage - this.page_size, this.page_size * this.tablepage);
        },
        getDoctors() {
            var _this = this;
            axios
                .get("get_doctors")
                .then(function(res) {
                    _this.doctor_list_compress = [];
                    _this.doctor_list_complete = [];
                    res.data.forEach(el => {
                        _this.doctor_list_compress.push(_this.trimToCompare(el.name));
                        _this.doctor_list_complete.push(el);
                    });
                })
                .catch(function(res) { });
        },
        uploadToDatabase() {
            var _this = this;
            _this.getDoctors();
            if(_this.excel_validation_error[0].length < 1 &&
                _this.excel_validation_error[1].length < 1 &&
                _this.excel_validation_error[2].length < 1 &&
                _this.preview_excel_sheet_data.length > 0
            ){
                var excel_data = [];
                excel_data = [{
                    doctor_record: _this.preview_excel_sheet_data,
                    import_batch: _this.import_batch,
                    doctor_list: _this.doctor_list_complete
                }];
                console.log(excel_data);
                axios
                .post("import_doctor_record", excel_data)
                .then(function(res) {
                    console.log(res.data);
                    _this.$notify({
                        type: 'success',
                        title: 'Import',
                        message: "Data imported successfully!",
                    });
                })
                .catch(function(res) { });
            }else{
                _this.$notify({
                    type: 'warning',
                    title: 'Import',
                    message: "Upload request error, please check your file.",
                });
            }
        },
        handleExceedFile(files, fileList) {
            this.$notify({
                type: 'info',
                title: 'Import',
                message: "You can only upload one file at a time",
            });
        },
        handleRemoveFile(file, fileList) {
            this.getDoctors();
            this.preview_excel_sheet_data = [];
            this.sheet_length = '';
            this.tablepage = 1;
            this.tablelength = 0;
            this.current_tab_content = [];
            this.exceldata = [];
            this.current_tab = '';
            this.excel_validation_error = [[], [], []];
            this.import_batch = [];
            this.doctor_list_compress = [];
            this.doctor_list_complete = [];
            this.is_preview = false;
            this.$notify({
                type: 'info',
                title: 'Cancelled',
                message: file.name + " was removed",
            });
        },
        trimToCompare(text) {
            return (text).trim().toLowerCase().replace(/\s/g, '');
        },
        checkSheetName(sheetname, position_in_object) {
            var split_scope = sheetname.split('-');
            var value_from, value_to = "";
            if(new Date(split_scope[0]) != "Invalid Date"){
                value_from = "valid";
            }
            if(new Date(split_scope[1]) != "Invalid Date"){
                value_to = "valid";
            }
            if(value_from == "valid" && value_to == "valid"){
                var date_from = new Date(split_scope[0]);
                var date_to = new Date(split_scope[1]);
                value_from = (date_from.getDate() < 10 ? '0' + date_from.getDate(): date_from.getDate()) + '' +
                             ((date_from.getMonth() + 1) < 10 ? '0' + (date_from.getMonth() + 1) : (date_from.getMonth() + 1)) + '' +
                             (date_from.getFullYear());
                value_to = (date_to.getDate() < 10 ? '0' + date_to.getDate(): date_to.getDate()) + '' +
                             ((date_to.getMonth() + 1) < 10 ? '0' + (date_to.getMonth() + 1) : (date_to.getMonth() + 1)) + '' +
                             (date_to.getFullYear());
                if((value_from + "-" + value_to).length == 17){
                   this.import_batch.push( value_from + "-" + value_to);
                   return "valid";
                }else{
                   this.import_batch.push( "000-000" );
                   return "invalid";
                }
            }else{
                this.import_batch.push( "000-000" );
                return "invalid";
            }
        },
        fileData(file, fileList) {
            var _this = this;
            var header_required = [
                "patient_name",
                "admission_date",
                "discharge_date",
                "total_pf",
                "attending_physician",
                "admitting_physician",
                "requesting_physician",
                "co_management",
                "anesthesiologist_physician",
                "surgeon_physician",
                "healthcare_physician",
                "er_physician",
                "is_private"
            ];
            var reader = new FileReader();
            reader.readAsArrayBuffer(file.raw);
            reader.onloadend = function(e) {
                var data = new Uint8Array(reader.result);
                var wb = XLSX.read(data,{type:'array', cellDates:true, dateNF:'dd.mm.yyyy h:mm:ss AM/PM'});
                _this.sheet_length = wb.SheetNames.length;
                for (let i = 0; i < wb.SheetNames.length; i++) {
                    let sheetName = wb.SheetNames[i];
                    let worksheet = wb.Sheets[sheetName];
                    if(_this.checkSheetName(sheetName ,i) == "invalid"){
                        _this.excel_validation_error[0].push({
                            id: 'ws' + (Math.random().toString(36).substring(7)) + (i + 1),
                            value: sheetName + " - ",
                            message: "invalid format ",
                            cell_position: 'worksheet #' + (i + 1),
                        });
                    }
                    try {
                        var range = XLSX.utils.decode_range(worksheet['!ref']);
                        if(range.e.r < 1){
                            _this.excel_validation_error[2].push({
                                id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                value: '',
                                message: "It looks like you don't have any data in this page ",
                                cell_position: 'worksheet #' + (i + 1),
                            });
                        }
                    } catch (error) {}
                    for(var R = range.s.r; R <= range.e.r; ++R) {
                        for(var C = range.s.c; C <= range.e.c; ++C) {
                            var cellref = XLSX.utils.encode_cell({c:C, r:R});
                            var cell_position = "#"+ (i + 1) + " " + ((C + 1) + 9).toString(36).toUpperCase() + (R + 1);
                            if(!worksheet[cellref]){
                                if(R == 0 && C < 13 ){
                                    _this.excel_validation_error[1].push({
                                        id: 'wsh' + (Math.random().toString(36).substring(7)) + (i + 1),
                                        value: '',
                                        message: "Header must have 13 column.",
                                        cell_position: cell_position,
                                    });
                                }
                                if(R > 0 && C < 13){
                                    if(C == 3){
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: '',
                                            message: "This cell can only contain numbers",
                                            cell_position: cell_position,
                                        });
                                    }else if(C == 12){
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: '',
                                            message: "This cell must contain '0 or 1' only ",
                                            cell_position: cell_position,
                                        });
                                    }else if(C == 0){
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: '',
                                            message: "This cell must contain Patient Name, format(LastName, FirstName MiddleName) ",
                                            cell_position: cell_position,
                                        });
                                    }else if(C == 1 || C == 2){
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: '',
                                            message: "This cell must contain 'DATETIME' format(Month/Day/Year Hour:Minutes:Second AM or PM) ",
                                            cell_position: cell_position,
                                        });
                                    }else{
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: '',
                                            message: "This cell must contain 'NULL' or Physician Name, format(LastName, FirstName MiddleName). If you need to add more physician, a comma delimeter ',' is required.",
                                            cell_position: cell_position,
                                        });
                                    }
                                }
                                continue;
                            }
                            var cell = worksheet[cellref];
                            if(R == 0 && C < 13){
                                var column_cell = _this.trimToCompare(cell.v);
                                if(header_required.indexOf(column_cell) == "-1"){
                                    _this.excel_validation_error[1].push({
                                        id: 'wsh' + (Math.random().toString(36).substring(7)) + (i + 1),
                                        value: cell.v,
                                        message: "Required header did not match, download the sample excel file",
                                        cell_position: cell_position,
                                    });
                                }
                            }
                            if(R > 0 && C < 13){
                                if(C == 1){
                                }else if (C == 3) {
                                    if(isNaN(cell.v % 1)){
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: cell.v,
                                            message: "must only contain numbers",
                                            cell_position: cell_position,
                                        });
                                    }
                                }else if(C > 3 && C < 12){
                                    if(cell.v == "NULL"){
                                    }else{
                                        if(cell.v.match(/[^,]+,[^,]+/g) == null){
                                            _this.excel_validation_error[2].push({
                                                id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                                value: cell.v,
                                                message: "must contain 'NULL' or Physician Name, format(LastName, FirstName MiddleName) ",
                                                cell_position: cell_position,
                                            });
                                        }else if(cell.v.match(/[^,]+,[^,]+/g).length > 1){
                                            var compress_to_compare = "";
                                            for (let index = 0; index < cell.v.match(/[^,]+,[^,]+/g).length; index++) {
                                                compress_to_compare += cell.v.match(/[^,]+,[^,]+/g)[index] + ",";
                                                if(!_this.doctor_list_compress.includes(_this.trimToCompare(cell.v.match(/[^,]+,[^,]+/g)[index]))){
                                                    _this.excel_validation_error[2].push({
                                                        id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                                        value: (cell.v.match(/[^,]+,[^,]+/g)[index]),
                                                        message: "does not exist in the database please add it manually to proceed ",
                                                        cell_position: cell_position,
                                                    });
                                                }
                                            }
                                            if(_this.trimToCompare(compress_to_compare) != _this.trimToCompare((cell.v + ","))){
                                                _this.excel_validation_error[2].push({
                                                    id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                                    value: cell.v,
                                                    message: "must contain 'NULL' or Physician Name, format(LastName, FirstName MiddleName). If you need to add more physician, a comma delimeter ',' is required.",
                                                    cell_position: cell_position,
                                                });
                                            }
                                        }else if(cell.v.match(/[^,]+,[^,]+/g).length == 1){
                                            if(_this.trimToCompare(cell.v.match(/[^,]+,[^,]+/g)[0]) != _this.trimToCompare((cell.v))){
                                                _this.excel_validation_error[2].push({
                                                    id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                                    value: cell.v,
                                                    message: "must contain 'NULL' or Physician Name, format(LastName, FirstName MiddleName). If you need to add more physician, a comma delimeter ',' is required.",
                                                    cell_position: cell_position,
                                                });
                                            }else{
                                                if(!_this.doctor_list_compress.includes(_this.trimToCompare(cell.v.match(/[^,]+,[^,]+/g)[0]))){
                                                    _this.excel_validation_error[2].push({
                                                        id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                                        value: (cell.v.match(/[^,]+,[^,]+/g)[0]),
                                                        message: "does not exist in the database please add it manually to proceed ",
                                                        cell_position: cell_position,
                                                    });
                                                }
                                            }
                                        }
                                    }
                                }else if(C == 12){
                                    if(cell.v == 0 || cell.v == 1){
                                    }else{
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: cell.v,
                                            message: "must contain '0 or 1' only",
                                            cell_position: cell_position,
                                        });
                                    }
                                }
                            }
                        }
                    }
                    _this.preview_excel_sheet_data.push({
                        title: sheetName,
                        name: i,
                        content: XLSX.utils.sheet_to_json(worksheet),
                        nameoftab: sheetName.replace(/\s/g, ''),
                        batch: ''
                    });
                    _this.current_tab_content.push(XLSX.utils.sheet_to_json(worksheet));
                }
                if(_this.excel_validation_error[0].length < 1 &&
                    _this.excel_validation_error[1].length < 1 &&
                    _this.excel_validation_error[2].length < 1
                ){
                    _this.is_preview = true;
                }else{
                    _this.is_preview = false;
                }
                _this.defaultTabSelected();
            }
        },
        handleClickTab(tab, event) {
            this.current_tab = tab.index;
            this.tablelength = this.current_tab_content[tab.index].length;
            this.exceldata = this.current_tab_content[tab.index];
            this.exceldata = this.exceldata.slice(this.page_size * this.tablepage - this.page_size, this.page_size * this.tablepage);
        },
        defaultTabSelected() {
            this.current_tab = 0;
            this.tablelength = this.current_tab_content[0].length;
            this.exceldata = this.current_tab_content[0];
            this.tablepage = parseInt(1);
            this.exceldata = this.exceldata.slice(this.page_size * this.tablepage - this.page_size, this.page_size * this.tablepage);
        },
        exportExcel() {
            this.export_excel = [];
            this.data.forEach((record)=>{
                this.export_excel.push({
                    Patient_Name: record.patient_name,
                    Admission_Date: this.getDateTime(record.admission_date),
                    Discharge_Date: this.getDateTime(record.discharge_date),
                    Total_PF: record.total,
                    Attending_Physician: this.changeDelimeter(record.allattending),
                    Admitting_Physician: this.changeDelimeter(record.alladmitting),
                    Requesting_Physician: this.changeDelimeter(record.allrequesting),
                    Co_Management: this.changeDelimeter(record.allcomanagement),
                    Anesthesiology_Physician: this.changeDelimeter(record.allanesthesiologist),
                    Surgeon_Physician: this.changeDelimeter(record.allsurgeon),
                    HealthCare_Physician: this.changeDelimeter(record.allhealthcare),
                    ER_Physician: this.changeDelimeter(record.aller),
                    Is_Private: (record.record_type == "private")? 1 : 0,
                });
            });
            var sheet_name;
            if (typeof this.value[0] !== 'undefined' || this.value[0] == 'All') {
                if (this.value[0] == 'All') {
                    sheet_name = "All Record";
                } else {
                    var month_name = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
                    var d = (this.value[0]).trim().split('-');
                    var date_from = month_name[parseInt(d[0][2]+d[0][3]) - 1] + " " + d[0][0]+d[0][1]+" "+d[0][4]+d[0][5]+d[0][6]+d[0][7];
                    var date_to = month_name[parseInt(d[1][2]+d[1][3]) - 1] + " " + d[1][0]+d[1][1]+" "+d[1][4]+d[1][5]+d[1][6]+d[1][7];
                    sheet_name = date_from + " - " + date_to;
                }
                var acpn_rec = XLSX.utils.json_to_sheet(this.export_excel);
                var wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, acpn_rec, sheet_name);
                XLSX.writeFile(wb, 'Record_List.xlsx');
            } else {
                this.$notify({
                    type: 'warning',
                    title: 'Export',
                    message: "Please select batch to proceed",
                });
            }
        },
        changeDelimeter(physician) {
            if(physician != "" && physician != "; " && physician != ";"){
                return (physician.replaceAll(";", ",")).trim().slice(0, -1);
            }else{
                return "NULL";
            }
        },
        getDateTime(dt) {
            var date = false;
            if (dt) {
                var c_date = new Date(dt);
                var dates = (c_date.getMonth()) + 1 + "/" + c_date.getDate() + "/" + c_date.getFullYear();
                var hrs = c_date.getHours();
                var min = c_date.getMinutes();
                var sec = c_date.getSeconds();
                if (isNaN(hrs) || isNaN(min) || isNaN(sec) || c_date === "Invalid Date") {
                    return null;
                }
                var type = (hrs <= 12) ? " AM" : " PM";
                date = dates + " "+ ((+hrs % 12) || hrs) + ":" + min + ":" + sec + type;
            }
            return date;
        },
        formDialog(key) {
            switch (key) {
                case "import_data":
                        this.dialogtitle = 'Import Excel';
                        this.fullscreen = true;
                        this.isimport = true;
                        this.dialogExcelFile = true;
                    break;
                case "export_data":
                        this.dialogtitle = 'Download / Export Excel';
                        this.fullscreen = false;
                        this.isimport = false;
                        this.dialogExcelFile = true;
                    break;
                case "add_record":
                        this.triggerAdd();
                    break;
                case "delete_batch":
                    var _this = this;
                    if (this.value.length == 0 || this.value[0] == 'All' ) {
                       this.$notify({
                            type: 'info',
                            title: 'info',
                            message: "Please select batch to delete",
                        });
                    } else {
                        this.$confirm(`Are you sure you want to delete,\n selected batch: ${this.value[0]} ?`, "Confirm Delete", {
                                distinguishCancelAndClose: true,
                                confirmButtonText: "Delete",
                                cancelButtonText: "Cancel",
                                type: "warning"
                            }
                        ).then(() => {
                                axios.delete(`delete_recordBybatch/${this.value[0]}`)
                                .then(function(response) {
                                    if (response.status > 199 && response.status < 203) {
                                        _this.$notify({
                                            type: 'success',
                                            title: 'Delete',
                                            message: "Successfully deleted",
                                        });
                                    }
                                    _this.getBatch();
                                });
                            })
                            .catch(action => {
                                _this.$notify({
                                    type: 'info',
                                    title: 'Cancelled',
                                    message: "No Changes",
                                });
                            });
                    }
                    break;
            }
        },
        getTemplate() {
            window.open(window.location.origin+"/template/Import_Record_Template.xlsx");
        }
    },
    mounted() {
        this.getBatch();
        this.getDoctors();
    },
}
</script>
<style scoped>
  #search_box {
        margin-bottom: 10px;
    }
    .block-button, .block-button button {
        width: 100%;
    }
</style>
