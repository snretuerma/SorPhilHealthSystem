<template>
    <div>
         <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                <i class="fa fa-file-medical-alt"></i>&nbsp;&nbsp;Records
                </span>
            </div>
        </div>
        <!-- End Header -->

        <el-dropdown
            @command="formDialog"
            style="float:right;margin-left:3px;"
        >
            <el-button size="medium"
                >Excel<i class="el-icon-arrow-down el-icon--right"></i
            ></el-button>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item
                    icon="el-icon-upload2"
                    command="import_data"
                    >Import Data</el-dropdown-item
                >
                <el-dropdown-item
                    icon="el-icon-download"
                    command="export_data"
                    >Export Data</el-dropdown-item
                >
            </el-dropdown-menu>
        </el-dropdown>

        <!-- Import excel -->
        <el-dialog title="Import Excel" :visible.sync="dialogExcelFile" :fullscreen="true">
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
                        <el-button slot="trigger" type="primary" plain>select supported excel file</el-button>
                        <el-button type="success" @click="uploadToDatabase" :disabled="!is_preview">upload to database</el-button>
                    </el-upload>
                    <el-progress v-if="progressbar_import" :percentage="percentage" color="#409eff"></el-progress>
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
                                :data="data"
                                style="width: 100%"
                                height="350"
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
                                min-width="120">
                                </el-table-column>
                            </el-table>
                            <el-pagination
                                class="align-middle"
                                background
                                layout="prev, pager, next"
                                @current-change="handleCurrentChange"
                                :page-size="page_size"
                                :total="total"
                            />
                        </el-tab-pane>
                    </el-tabs>
                </el-col>
            </el-row>

            <!-- for excel error validation -->
            <div v-show="!is_preview && preview_excel_sheet_data.length > 0">
                <el-alert
                    title='ERROR FOUND, Please see error log bellow'
                    type="error"
                    :closable="false"
                    show-icon>
                </el-alert>
                <ul>
                    <dl>
                        <dt>WORKSHEET NAME</dt>
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
        </el-dialog>
        <!-- Import excel end-->

    </div>
</template>
<script>

import XLSX from 'xlsx';
export default {
    data() {
        return {
            progressbar_import: false,
            doctorRecord:[],
            dialogExcelFile: false,
            percentage: 0,
            preview_excel_sheetname:[],
            preview_excel_sheet_data:[],
            sheet_length: '',
            page: 1,
            page_size: 10,
            total: 0,
            current_tab_content:[],
            current_tab_table:[],
            data:[],
            current_tab: '',
            excel_validation_error:[[], [], []],
            import_batch:[],
            doctor_list_compress:[],
            doctor_list_complete:[],
            is_preview: false,
        };
    },
    methods: {
        covertDate(row, column, cellValue, index){
            var hours = cellValue.getHours();
            var minutes = cellValue.getMinutes();
            var ampm = hours >= 12 ? 'pm' : 'am';
            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0'+minutes : minutes;
            var strTime = hours + ampm;
            return cellValue.getFullYear() + "-" + (cellValue.getMonth() + 1) + "-" + cellValue.getDate() + " " + strTime;
        },
        handleCurrentChange(value) {
            this.page = value;
            this.total = this.current_tab_content[this.current_tab].length;
            this.data = this.current_tab_content[this.current_tab];
            this.data = this.data.slice(this.page_size * this.page - this.page_size, this.page_size * this.page);
        },
        getDoctors(){
            var _this = this;
            axios
                .get("get_doctors")
                .then(function(res) {
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
                var excel_data = [{
                    doctor_record: _this.preview_excel_sheet_data,
                    import_batch: _this.import_batch,
                    doctor_list: _this.doctor_list_complete
                }];
                axios
                .post("import_doctor_record", excel_data)
                .then(function(res) {
                    _this.$notify({
                        type: 'success',
                        title: 'Import',
                        message: "Successfully imported",
                    });
                })
                .catch(function(res) { });
            }else{
                _this.$notify({
                    type: 'warning',
                    title: 'Import',
                    message: "Please re-import",
                });
            }
        },
        handleExceedFile(files, fileList) {
            this.$notify({
                type: 'info',
                title: 'Import',
                message: "1 File limit please remove selected to re-select again",
            });
        },
        handleRemoveFile(file, fileList) {
            this.getDoctors();
            this.doctorRecord = [];
            this.preview_excel_sheetname = [];
            this.preview_excel_sheet_data = [];
            this.sheet_length = '';
            this.page = 1;
            this.total = 0;
            this.current_tab_content = [];
            this.current_tab_table = [];
            this.data = [];
            this.current_tab = '';
            this.excel_validation_error = [[], [], []];
            this.import_batch = [];
            this.doctor_list_compress = [];
            this.doctor_list_complete = [];
            this.is_preview = false;
            this.$notify({
                type: 'info',
                title: 'Cancel',
                message: 'Successfully cancel upload ' + file.name,
            });
        },
        trimToCompare(text){
            return (text).trim().toLowerCase().replace(/\s/g, '');
        },
        checkSheetName(sheetname, position_in_object){
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
        fileData(file, fileList){
            var _this = this;
            this.doctorRecord.push(file.raw);
            var header_required = [
                "patient_name",
                "admission_date",
                "discharge_date",
                "total_pf",
                "attending_physician",
                "admitting_physician",
                "requesting_physician",
                "reffered_physician",
                "co_management",
                "anesthesiology_physician",
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
                            message: "invalid sheet name format please check",
                            cell_position: 'worksheet number ' + (i + 1),
                        });
                    }
                    try {
                        var range = XLSX.utils.decode_range(worksheet['!ref']);
                        if(range.e.r < 1){
                            _this.excel_validation_error[2].push({
                                id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                value: '',
                                message: "each worksheet required input",
                                cell_position: 'worksheet number ' + (i + 1),
                            });
                        }
                    } catch (error) {}
                    for(var R = range.s.r; R <= range.e.r; ++R) {
                        for(var C = range.s.c; C <= range.e.c; ++C) {
                            var cellref = XLSX.utils.encode_cell({c:C, r:R});
                            var cell_position = "#"+ (i + 1) + " " + ((C + 1) + 9).toString(36).toUpperCase() + (R + 1);
                            //console.log( "console me:", XLSX.utils.sheet_to_row_object_array(wb.Sheets[sheetName]).length);
                            if(!worksheet[cellref]){
                                if(R == 0 && C < 14 ){
                                    _this.excel_validation_error[1].push({
                                        id: 'wsh' + (Math.random().toString(36).substring(7)) + (i + 1),
                                        value: '',
                                        message: "column header required no text found, must be 14 column",
                                        cell_position: cell_position,
                                    });
                                }
                                if(R > 0 && C < 14){
                                    if(C == 3){
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: '',
                                            message: "all cell required or must be 'INTEGER' instead",
                                            cell_position: cell_position,
                                        });
                                    }else if(C == 13){
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: '',
                                            message: "all cell required or must be '0 or 1' instead",
                                            cell_position: cell_position,
                                        });
                                    }else if(C == 0){
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: '',
                                            message: "all cell required or must be 'NOT NULL' instead",
                                            cell_position: cell_position,
                                        });
                                    }else if(C == 1 || C == 2){
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: '',
                                            message: "all cell required or must be 'DATETIME' instead",
                                            cell_position: cell_position,
                                        });
                                    }else{
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: '',
                                            message: "all cell required or must be 'NULL' instead !! PHYSICIAN",
                                            cell_position: cell_position,
                                        });
                                    }
                                }
                                continue;
                            }
                            var cell = worksheet[cellref];
                            if(R == 0 && C < 14){
                                var column_cell = _this.trimToCompare(cell.v);
                                if(header_required.indexOf(column_cell) == "-1"){
                                    _this.excel_validation_error[1].push({
                                        id: 'wsh' + (Math.random().toString(36).substring(7)) + (i + 1),
                                        value: cell.v,
                                        message: "column header required not match",
                                        cell_position: cell_position,
                                    });
                                }
                            }
                            if(R > 0 && C < 14){
                                if(C == 1){
                                }else if (C == 3) {
                                    if(isNaN(cell.v % 1)){
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: cell.v,
                                            message: "deprecated must be a  number",
                                            cell_position: cell_position,
                                        });
                                    }
                                }else if(C > 3 && C < 13){
                                    if(cell.v == "NULL"){
                                    }else{
                                        if(cell.v.match(/[^,]+,[^,]+/g) == null){
                                            _this.excel_validation_error[2].push({
                                                id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                                value: cell.v,
                                                message: "name must be a correct format",
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
                                                        message: "name not exist into database please add to proceed...",
                                                        cell_position: cell_position,
                                                    });
                                                }
                                            }
                                            if(_this.trimToCompare(compress_to_compare) != _this.trimToCompare((cell.v + ","))){
                                                _this.excel_validation_error[2].push({
                                                    id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                                    value: cell.v,
                                                    message: "there was a proble with the name format value not match",
                                                    cell_position: cell_position,
                                                });
                                            }
                                        }else if(cell.v.match(/[^,]+,[^,]+/g).length == 1){
                                            if(_this.trimToCompare(cell.v.match(/[^,]+,[^,]+/g)[0]) != _this.trimToCompare((cell.v))){
                                                _this.excel_validation_error[2].push({
                                                    id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                                    value: cell.v,
                                                    message: "there was a proble with the name format value not match",
                                                    cell_position: cell_position,
                                                });
                                            }else{
                                                if(!_this.doctor_list_compress.includes(_this.trimToCompare(cell.v.match(/[^,]+,[^,]+/g)[0]))){
                                                    _this.excel_validation_error[2].push({
                                                        id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                                        value: (cell.v.match(/[^,]+,[^,]+/g)[0]),
                                                        message: "name not exist into database please add to proceed...",
                                                        cell_position: cell_position,
                                                    });
                                                }
                                            }
                                        }
                                    }
                                }else if(C == 13){
                                    if(cell.v == 0 || cell.v == 1){
                                    }else{
                                        _this.excel_validation_error[2].push({
                                            id: 'wsc' + (Math.random().toString(36).substring(7)) + (i + 1),
                                            value: cell.v,
                                            message: "is private must be 0 or 1 only",
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
        handleClickTab(tab, event){
            this.current_tab = tab.index;
            this.total = this.current_tab_content[tab.index].length;
            this.data = this.current_tab_content[tab.index];
            this.page = parseInt(1);
            this.data = this.data.slice(this.page_size * this.page - this.page_size, this.page_size * this.page);
        },
        defaultTabSelected(){
            this.current_tab = 0;
            this.total = this.current_tab_content[0].length;
            this.data = this.current_tab_content[0];
            this.page = parseInt(1);
            this.data = this.data.slice(this.page_size * this.page - this.page_size, this.page_size * this.page);
        },
        formDialog: function(key) {
            switch (key) {
                case "import_data":
                      this.dialogExcelFile = true
                    break;
                default:
                    break;
            }
        },
    },
    mounted() {
        this.getDoctors();
    },
    computed: {
    }

}
</script>