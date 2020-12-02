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

       <!-- <el-upload
        class="upload-demo"
        action="https://jsonplaceholder.typicode.com/posts/"
        :on-preview="handlePreview"
        :on-remove="handleRemove"
        :before-remove="beforeRemove"
        multiple
        :limit="3"
        :on-exceed="handleExceed"
        :file-list="fileList">
        <el-button size="small" type="primary">Click to upload</el-button>
        <div slot="tip" class="el-upload__tip">jpg/png files with a size less than 500kb</div>
        </el-upload>

        <el-upload
  class="upload-demo"
  ref="upload"
  action="import_doctor_record"
  :auto-upload="false"
  :on-change="fileData"
  >
  <el-button slot="trigger" size="small" type="primary">select file</el-button>
  <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">upload to server</el-button>
  <div class="el-upload__tip" slot="tip">jpg/png files with a size less than 500kb</div>
</el-upload>-->

        <!-- Import budget via excel file-->
        <div
            class="modal fade"
            id="importModal"
            tabindex="-1"
            aria-labelledby="ModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">
                            Import Budget
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form
                        method="post"
                        enctype="multipart/form-data"
                        action="/budget_import"
                    >
                        <input type="hidden" name="" id="" />
                        <div class="modal-body">
                            <div class="form-group">
                                <input
                                    type="hidden"
                                    name="i_action"
                                    id="i_action"
                                    value="BudgetImport"
                                />
                                <label
                                    >Select excel file for upload (.csv)</label
                                ><br />
                                <input
                                    type="file"
                                    @change="selectFile($event)"
                                    id="excelcontent"
                                    name="budgets"
                                    accept=".csv"
                                    class="w-100"
                                    style="border:1px solid rgba(0,0,0,0.1);border-radius:4px;"
                                />
                                <div
                                    v-if="progressbar_import"
                                    class="progress"
                                    style="margin-top:15px;"
                                >
                                    <div
                                        class="progress-bar progress-bar-striped active"
                                        role="progressbar"
                                        aria-valuenow="0"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                        style="width:0%"
                                    >
                                        0%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                            <button
                                type="button"
                                name="upload"
                                class="btn btn-primary"
                                v-on:click="
                                    progressbar_import = true;
                                    onSubmit();
                                "
                                v-bind:disabled="enableUpload === false"
                            >
                                Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Import excel end-->

        <!-- Import excel -->
        <el-dialog title="Import Excel" :visible.sync="dialogExcelFile" :fullscreen="true">
            <el-row>
                <el-col>
                    <span class="demonstration">Select Batch Date</span>
                    <el-date-picker
                        v-model="value2"
                        type="daterange"
                        align="right"
                        style="width:100%"
                        unlink-panels
                        range-separator="To"
                        start-placeholder="Start date"
                        end-placeholder="End date"
                        :picker-options="pickerOptions">
                    </el-date-picker>
                </el-col>
            </el-row><br>
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
                        <el-button type="success" @click="uploadToDatabase">upload to database</el-button>

                    </el-upload>
                    <el-progress v-if="progressbar_import" :percentage="percentage" color="#409eff"></el-progress>
                </el-form-item>
            </el-form>
            <el-row>
                <el-col>
                    <el-tabs type="border-card" tab-position="bottom" @tab-click="handleClickTab">
                        <!--<el-tab-pane>
                            <span slot="label"><i class="el-icon-date"></i> Route</span>
                            Route
                        </el-tab-pane>
                        <el-tab-pane label="Config">Config</el-tab-pane>
                        <el-tab-pane label="Role">Role</el-tab-pane>
                        <el-tab-pane label="Task">Task</el-tab-pane>-->
                        <el-tab-pane
                            v-for="(item,index) in preview_excel_sheet_data"
                            :key="item.name"
                            :label="item.title"
                            :name="item.nameoftab"
                        >
                        {{ item.title }}
                            <el-table
                                :data="data"
                                style="width: 100%"
                                height="350"
                                >
                                <!--<el-table-column v-for="(ite,index) in item.content"
                                                :key="item.name"
                                                :prop="item.name"
                                                :label="item.name"> {{ ite[index] }}

                                </el-table-column>-->
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
                            /><!---->
                        </el-tab-pane>
                    </el-tabs>
                </el-col>
            </el-row>
        </el-dialog>
        <!-- Import excel end-->

    </div>
</template>
<script>

import XLSX from 'xlsx';
/*
[ { "erewr": 1, "werwer": 2, "wer": 3, "werewrewrwr": 4 }, { "erewr": 5, "werwer": 6, "wer": 7, "werewrewrwr": 8 } ]
*/
export default {
    data() {
        return {
            progressbar_import: false,
            enableUpload: false,
            doctorRecord:[],
            dialogExcelFile: false,
            percentage: 0,
            preview_excel_sheetname:[],
            preview_excel_sheet_data:[],
            sheet_length: '',
            pickerOptions: {
                shortcuts: [{
                    text: 'Last week',
                    onClick(picker) {
                    const end = new Date();
                    const start = new Date();
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                    picker.$emit('pick', [start, end]);
                    }
                }, {
                    text: 'Last month',
                    onClick(picker) {
                    const end = new Date();
                    const start = new Date();
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                    picker.$emit('pick', [start, end]);
                    }
                }, {
                    text: 'Last 3 months',
                    onClick(picker) {
                    const end = new Date();
                    const start = new Date();
                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                    picker.$emit('pick', [start, end]);
                    }
                }]
                },
            value2: '',
            page: 1,
            page_size: 10,
            total: 0,
            current_tab_content:[],
            current_tab_table:[],
            data:[],
            current_tab: '',
            excel_validation_error:[],
            import_batch:[],
            doctor_list_compress:[],
            doctor_list_complete:[],

        };
    },
    methods: {
        covertDate(row, column, cellValue, index){
            /*const unixTimestamp = cellValue

            const milliseconds = cellValue * 1000 // 1575909015000

            const dateObject = new Date(milliseconds)

            const humanDateFormat = dateObject.toLocaleString() //2019-12-9 10:30:15

            dateObject.toLocaleString("en-US", {weekday: "long"}) // Monday
            dateObject.toLocaleString("en-US", {month: "long"}) // December
            dateObject.toLocaleString("en-US", {day: "numeric"}) // 9
            dateObject.toLocaleString("en-US", {year: "numeric"}) // 2019
            dateObject.toLocaleString("en-US", {hour: "numeric"}) // 10 AM
            dateObject.toLocaleString("en-US", {minute: "numeric"}) // 30
            dateObject.toLocaleString("en-US", {second: "numeric"}) // 15
            dateObject.toLocaleString("en-US", {timeZoneName: "short"}) // 12/9/2019, 10:30:15 AM CST
            //return humanDateFormat;

            var ts= cellValue; // say this is the format for decimal timestamp.
            var dt = new Date(1318781876.721 * 1000);
            var f = dt.toLocaleString();*/

            return cellValue.getFullYear() + "-" + (cellValue.getMonth() + 1) + "-" + cellValue.getDate() ;

            //console.log(dateObject);
        },
        handleCurrentChange(value) {
            this.page = value;

            this.total = this.current_tab_content[this.current_tab].length;
            this.data = this.current_tab_content[this.current_tab];

            this.data = this.data.slice(this.page_size * this.page - this.page_size, this.page_size * this.page);

        },
        getDoctors(){
            //get_doctors
            var _this = this;
            axios
                .get("get_doctors")
                .then(function(res) {
                    console.log(res.data);
                    res.data.forEach(el => {
                        _this.doctor_list_compress.push(_this.trimToCompare(el.name));
                        _this.doctor_list_complete.push(el);
                    });
                    
                    //this.doctor_list_compress.push();

                    //console.log(_this.doctor_list_compress);
                    

                    /*if(_this.doctor_list_compress.includes(_this.trimToCompare(" Lynch, Missouri Kertzmann"))){
                        console.log("exist");
                    }else{
                        console.log("not exist");
                    }*/
                })
                .catch(function(res) { });
        },

        uploadToDatabase() {
            var _this = this;
            //this.$refs.upload.submit();
            //var formData = new FormData();
            //console.log(formData.get('doctorRecord[]'));

            //console.log(this.doctorRecord[0]);

            /*var formData = new FormData();
            formData.append("doctorRecord[]", this.doctorRecord[0]);
            _this.progressbar_import = true;
                axios
                    .post("import_doctor_record", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data"
                        },
                        onUploadProgress: function(progressEvent) {
                            this.uploadPercentage = parseInt(
                                Math.round(
                                    (progressEvent.loaded * 100) /
                                        progressEvent.total
                                )
                            );
                            this.percentage = this.uploadPercentage;
                        }.bind(this)
                    })
                    .then(function(res) {
                        setTimeout(function() {
                            _this.progressbar_import = false;
                            _this.percentage = 0;
                            //_this.dialogExcelFile = false;
                            _this.$refs.upload.clearFiles();
                        }, 2000);
                        var total_imported = res.data;

                        if (total_imported == 0) {
                            _this.$notify({
                                type: 'warning',
                                title: 'Import',
                                message: "No row to be import",
                            });
                        } else if (total_imported > 0) {
                            _this.$notify({
                                type: 'success',
                                title: 'Import',
                                message: "Successfully imported: " + res.data + " row",
                            });
                            //_this.getBudget();
                        }
                    })
                    .catch(function(res) {
                        _this.progressbar_import = false;
                        _this.percentage = 0;
                        _this.dialogExcelFile = false;
                        _this.$notify({
                            type: 'error',
                            title: 'Message',
                            message: 'FAILURE!! Something went wrong!',
                        });
                    });*/

                    console.log(this.preview_excel_sheet_data);

                    var formData = new FormData();
                    formData.append("doctorRecord[]", this.preview_excel_sheet_data);
                    formData.append("import_batch[]", this.import_batch);
                    formData.append("doctor_list[]", this.doctor_list_complete);


                    axios
                    .post("import_doctor_record", formData)
                    .then(function(res) {



                            _this.$notify({
                                type: 'success',
                                title: 'Import',
                                message: "Successfully imported",
                            });


                    })
                    .catch(function(res) { });


        },
        handleExceedFile(files, fileList) {
                this.$message.warning(`The limit is 1, you selected ${files.length} files this time, add up to ${files.length + fileList.length} totally`);

        },
        handleRemoveFile(file, fileList) {
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
/**/
            if(value_from == "valid" && value_to == "valid"){
                var date_from = new Date(split_scope[0]);
                var date_to = new Date(split_scope[1]);
                value_from = (date_from.getDate() < 10 ? '0' + date_from.getDate(): date_from.getDate()) + '' +
                             ((date_from.getMonth() + 1) < 10 ? '0' + (date_from.getMonth() + 1) : (date_from.getMonth() + 1)) + '' +
                             (date_from.getFullYear());
                value_to = (date_to.getDate() < 10 ? '0' + date_to.getDate(): date_to.getDate()) + '' +
                             ((date_to.getMonth() + 1) < 10 ? '0' + (date_to.getMonth() + 1) : (date_to.getMonth() + 1)) + '' +
                             (date_to.getFullYear());
                            //console.log(value_from + "-" + value_to);
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
            console.log("---");
            console.log(file);
            console.log(fileList);
            console.log("---");

            var formData = new FormData();
            formData.append("doctorRecord[]", file.raw);

            //this.doctorRecord = [];
            this.doctorRecord.push(file.raw);

            //console.log(file.raw);

            var _this = this;

            var header_required = ["patient_name",
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
                                   "is_private"];


                //console.log(e.target.files[0]);
                var reader = new FileReader();
                reader.readAsArrayBuffer(file.raw);
                reader.onloadend = function(e) {
                    //console.log(e);
                        var data = new Uint8Array(reader.result);
                        var wb = XLSX.read(data,{type:'array', cellDates:true, dateNF:'dd.mm.yyyy h:mm:ss AM/PM'});
                       console.log("console me");
                        //console.log(wb.SheetNames.length);
                        //var htmlstr = XLSX.write(wb,{sheet:"sheet no1", type:'binary',bookType:'html'});
                        //$('#wrapper')[0].innerHTML += htmlstr;
                        //console.log(data);

                        var header_error_obj = [];

                         _this.sheet_length = wb.SheetNames.length;
                         for (let i = 0; i < wb.SheetNames.length; i++) {
                             //const element = array[i];
                                let sheetName = wb.SheetNames[i];
                                let worksheet = wb.Sheets[sheetName];
                                console.log(sheetName);

                                if(_this.checkSheetName(sheetName ,i) == "invalid"){
                                    _this.excel_validation_error.push({
                                        sheetname: sheetName,
                                        error: "invalid sheet name format please check",
                                        is_sheetname: 1
                                    });
                                    console.log("invalid inside valid");
                                }else{
                                    console.log("valid");
                                }

                               // console.log(wb.Sheets[sheetName]);
                               /* loop through every cell manually */
                                var range = XLSX.utils.decode_range(worksheet['!ref']); // get the range
                                //console.log(range);

                                //var header_error_obj = {};


                                //var column_count_validation = 0;
                                for(var R = range.s.r; R <= range.e.r; ++R) {
                                    for(var C = range.s.c; C <= range.e.c; ++C) {
                                        // find the cell object
                                        //console.log('Row : ' + R);
                                        //console.log('Column : ' + C);
                                        var cellref = XLSX.utils.encode_cell({c:C, r:R}); // construct A1 reference for cell
                                    /* console.log(worksheet[cellref]);*/

                                    var cell_position = ((C + 1) + 9).toString(36).toUpperCase() + (R + 1);



                                    if(!worksheet[cellref]){
                                        if(R == 0 && C < 14 ){
                                            //var location = ((C + 1) + 9).toString(36).toUpperCase();
                                            _this.excel_validation_error.push({
                                                sheetname: sheetName,
                                                row: R,
                                                column: C,
                                                location: cell_position,
                                                error: "column header required no text found, must be 14 column",
                                                value: '',
                                                is_header: 1
                                            });
                                        }
                                        if(R > 0 && C < 14){
                                            switch (C) {
                                                case 3:
                                                    console.log(C + "-" + R + "-" + "no value");

                                                    break;

                                                default:
                                                    break;
                                            }
                                        }
                                        continue;
                                    } // if cell doesn't exist, move on
                                        /*var cell = worksheet[cellref];
                                        console.log(cell.v);*/
                                        var cell = worksheet[cellref];
                                        if(R == 0 && C < 14){
                                        //console.log(R + "--" + C + "--" + cell.v);
                                        /*if(C == 0 && _this.trimToCompare(cell.v) == _this.trimToCompare("Patient_Name")){

                                            header_error_obj.push({
                                                "row": R,
                                                "column": C,
                                                "error": "1 some text" });
                                        }else if(C == 1 && _this.trimToCompare(cell.v) == _this.trimToCompare("Admission_Date")){
                                            header_error_obj.push({
                                                "row": R,
                                                "column": C,
                                                "error": "2 some text" });
                                        }
                                        column_count_validation = C;*/
                                        var column_cell = _this.trimToCompare(cell.v);
                                        if(header_required.indexOf(column_cell) == "-1"){
                                            //var location = ((C + 1) + 9).toString(36).toUpperCase();
                                            _this.excel_validation_error.push({
                                                sheetname: sheetName,
                                                row: R,
                                                column: C,
                                                location: cell_position,
                                                error: "column header required not match",
                                                value: cell.v,
                                                is_header: 1
                                            });
                                        }
                                        //console.log(cell.v);

                                        }
                                        if(R > 0 && C < 14){
                                            if(C == 1){
                                                //console.log(cell.v);
                                               // console.log(cell.v.match("^(([0]?[1-9]|1[0-2])/([0-2]?[0-9]|3[0-1])/[1-2]\d{3}) (20|21|22|23|[0-1]?\d{1}):([0-5]?\d{1})$"));
                                            }else if (C == 3) {
                                                    if(isNaN(cell.v % 1)){
                                                        //console.log("deprecated must be a  number");
                                                        //var location = ((C + 1) + 9).toString(36).toUpperCase();
                                                        _this.excel_validation_error.push({
                                                            sheetname: sheetName,
                                                            row: R,
                                                            column: C,
                                                            location: cell_position,
                                                            error: "deprecated must be a  number",
                                                            value: cell.v,
                                                            is_header: 0
                                                        });
                                                    }else{
                                                       // console.log(C + "-" + R + "-" + cell.v);
                                                    }
                                            }else if(C > 3 && C < 13){
                                                    //name_format_check = cell.v.match(/[^,]+,[^,]+/g);
                                                    if(cell.v == "NULL"){
                                                    }else{
                                                        /*if(cell.v.match(/[^,]+,[^,]+/g) == null){
                                                            //var location = ((C + 1) + 9).toString(36).toUpperCase();
                                                            _this.excel_validation_error.push({
                                                                sheetname: sheetName,
                                                                row: R,
                                                                column: C,
                                                                location: cell_position,
                                                                error: "name must be a correct format",
                                                                value: cell.v,
                                                                is_header: 0
                                                            });
                                                        }*/
                                                        //console.log(cell.v.match(/[^,]+,[^,]+/g));
                                                        //console.log(cell.v.match(/[^,]+,[^,]+/g).length);


                                                            /*if(!cell.v.match(/[^,]+,[^,]+/g)){
                                                                console.log("match");
                                                            }else{
                                                                console.log("NOT not match");
                                                            }*/
                                                        if(cell.v.match(/[^,]+,[^,]+/g) == null){
                                                            //var location = ((C + 1) + 9).toString(36).toUpperCase();
                                                            _this.excel_validation_error.push({
                                                                sheetname: sheetName,
                                                                row: R,
                                                                column: C,
                                                                location: cell_position,
                                                                error: "name must be a correct format",
                                                                value: cell.v,
                                                                is_header: 0
                                                            });
                                                        }else if(cell.v.match(/[^,]+,[^,]+/g).length > 1){
                                                            var compress_to_compare = "";
                                                            for (let index = 0; index < cell.v.match(/[^,]+,[^,]+/g).length; index++) {
                                                               // const element = array[index];
                                                               //if(cell.v.match(/[^,]+,[^,]+/g) == null){
                                                                 //console.log(index + "--" + cell.v.match(/[^,]+,[^,]+/g));
                                                                 //console.log(index + "--" + cell.v.match(/[^,]+,[^,]+/g)[index]);
                                                                 compress_to_compare += cell.v.match(/[^,]+,[^,]+/g)[index] + ",";
                                                                 //compress_to_compare.concat(el);
                                                                 //console.log(index + "--" + cell.v);
                                                              // }
                                                              //console.log("--" + cell.v.match(/[^,]+,[^,]+/g)[index] + ",");
                                                                
                                                                /*//CHECK IF EXIST IN DATABASE
                                                                if(!_this.doctor_list_compress.includes(_this.trimToCompare(cell.v.match(/[^,]+,[^,]+/g)[index]))){
                                                                    _this.excel_validation_error.push({
                                                                        sheetname: sheetName,
                                                                        row: R,
                                                                        column: C,
                                                                        location: cell_position,
                                                                        error: "name not exist into database please add to proceed...",
                                                                        value: (cell.v.match(/[^,]+,[^,]+/g)[index]),
                                                                        is_header: 0
                                                                    });
                                                                }
                                                                //END HERE*/

                                                            }//console.log("[more than  > 1]:" + cell.v);
                                                            if(_this.trimToCompare(compress_to_compare) != _this.trimToCompare((cell.v + ","))){
                                                                //console.log("not match row:" + R + " column:" +  C);
                                                                _this.excel_validation_error.push({
                                                                    sheetname: sheetName,
                                                                    row: R,
                                                                    column: C,
                                                                    location: cell_position,
                                                                    error: "there was a proble with the name format value not match",
                                                                    value: cell.v,
                                                                    is_header: 0
                                                                });
                                                            }

                                                        }/* CHECK IF EXIST INTO DATABASE / else if(cell.v.match(/[^,]+,[^,]+/g).length == 1){
                                                            if(_this.trimToCompare(cell.v.match(/[^,]+,[^,]+/g)[0]) != _this.trimToCompare((cell.v))){
                                                                _this.excel_validation_error.push({
                                                                    sheetname: sheetName,
                                                                    row: R,
                                                                    column: C,
                                                                    location: cell_position,
                                                                    error: "there was a proble with the name format value not match",
                                                                    value: cell.v,
                                                                    is_header: 0
                                                                });
                                                            }else{
                                                                if(!_this.doctor_list_compress.includes(_this.trimToCompare(cell.v.match(/[^,]+,[^,]+/g)[0]))){
                                                                    _this.excel_validation_error.push({
                                                                        sheetname: sheetName,
                                                                        row: R,
                                                                        column: C,
                                                                        location: cell_position,
                                                                        error: "name not exist into database please add to proceed...",
                                                                        value: (cell.v.match(/[^,]+,[^,]+/g)[0]),
                                                                        is_header: 0
                                                                    });
                                                                }
                                                            }
                                                        }*/ console.log("--------------------------------------------------");
                                                    }

                                                   // console.log(cell.v.match(/[^,]+,[^,]+/g));
                                            }else if(C == 13){
                                                    if(cell.v == 0 || cell.v == 1){
                                                    }else{
                                                            _this.excel_validation_error.push({
                                                                sheetname: sheetName,
                                                                row: R,
                                                                column: C,
                                                                location: cell_position,
                                                                error: "is private must be 0 or 1 only",
                                                                value: cell.v,
                                                                is_header: 0
                                                            });
                                                    }
                                            }
                                        }
                                    }
                                }
                                //console.log("Column:" + column_count_validation);


                                /*this.preview_excel.push({
                                    title: sheetName,
                                    name: i,
                                    content: 'Tab 1 content'
                                });*/
                                //_this.preview_excel_sheetname = wb.SheetNames;
                                //_this.preview_excel_sheetname = XLSX.utils.sheet_to_json(worksheet);
                                _this.preview_excel_sheet_data.push({
                                    title: sheetName,
                                    name: i,
                                    content: XLSX.utils.sheet_to_json(worksheet),
                                    nameoftab: sheetName.replace(/\s/g, ''),
                                    batch: ''
                                });
                                console.log(XLSX.utils.sheet_to_json(worksheet));

                                _this.current_tab_content.push(XLSX.utils.sheet_to_json(worksheet));




                               //_this.activeName = (_this.preview_excel_sheet_data[0].title + "0").replace(/\s/g, '');


                                //var rowObj =XLSX.utils.sheet_to_json(wb.Sheets[sheetName]);
                                //console.log(JSON.stringify(rowObj));

                                //console.log(XLSX.utils.sheet_to_json(worksheet).length);

                                /*for (let index = 0; index < XLSX.utils.sheet_to_json(worksheet).length; index++) {
                                    //const element = array[index];
                                    var a = XLSX.utils.sheet_to_json(worksheet);
                                    //console.log(a.indexOf(a[index]));

                                    console.log(a[a.indexOf(a[index])]);
                                    /*var obj = JSON.parse(a[a.indexOf(a[index])]);
                                    var n = Object.keys(obj).map(function (key,i) {
                                                return obj[key];
                                            });
                                            console.log(n);*/

                                /*}*/

                                  console.log("======END OF WORKSHEET====================================================================================================");


                         }
                         //_this.excel_validation_error.push(header_error_obj);

                         //console.log("--");
                         //console.log(JSON.stringify(_this.preview_excel_sheetname));

                        /*console.log("gg");
                var gg = JSON.stringify(_this.preview_excel);
                console.log(gg);*/

                } /**/


                /*console.log(e.target.files[0]);
                var reader = new FileReader();
                reader.readAsArrayBuffer(e.target.files[0]);
                reader.onload = function(e) {
                        var data = new Uint8Array(reader.result);
                        var wb = XLSX.read(data,{type:'array'});

                        console.log(wb);
                        var htmlstr = XLSX.write(wb,{sheet:"sheet no1", type:'binary',bookType:'html'});
                        $('#wrapper')[0].innerHTML += htmlstr;
                }*/



        },
        handleClickTab(tab, event){
           // console.log(tab.index, event);

           this.current_tab = tab.index;

            //var contentdata = this.preview_excel_sheet_data[tab.index].content;
            //console.log(contentdata);
            //this.current_tab_table = contentdata;
            //this.current_tab_table = this.current_tab_content[tab.index];
            //this.page = this.current_tab_content[tab.index];
            this.total = this.current_tab_content[tab.index].length;
            this.data = this.current_tab_content[tab.index];

            //console.log(this.tableData);
            //this.page = parseInt(tab.index) + 1;
            this.page = parseInt(1);

           // var a = this.data.slice(this.pageSize * this.page - this.pageSize, this.pageSize * this.page);
            console.log("console me");
            console.log(this.data.slice(this.page_size * this.page - this.page_size, this.page_size * this.page));
            console.log(this.total);
            //console.log(this.data.slice(this.page_size * this.page - this.page_size, this.page_size * this.page));
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
        selectFile(event) {
            if (event.target.value) {
                this.enableUpload = true;
            } else {
                this.enableUpload = false;
            }
        },
        onSubmit() {
            var _this = this;
            var formData = new FormData();
            //formData.append("i_action", $("#i_action").val());
            //formData.append("budgets[]", $("#excelcontent").get(0).files[0]);

            console.log($("#excelcontent").get(0).files[0]);

        console.log($(".el-upload__input").get(0).files[0]);

            /*axios
                .post("import_doctor_record", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    },
                    onUploadProgress: function(progressEvent) {
                        this.uploadPercentage = parseInt(
                            Math.round(
                                (progressEvent.loaded * 100) /
                                    progressEvent.total
                            )
                        );

                        $(".progress-bar")
                            .css("width", this.uploadPercentage + "%")
                            .attr("aria-valuenow", this.uploadPercentage);
                        $(".progress-bar").html(this.uploadPercentage + "%");
                    }.bind(this)
                })
                .then(function(res) {
                    setTimeout(function() {
                        _this.progressbar_import = false;
                        $(".progress-bar")
                            .css("width", "0%")
                            .attr("aria-valuenow", 0);
                        $(".progress-bar").html("0%");
                        $("#importModal").modal("hide");
                        $("#excelcontent").val("");
                    }, 2000);
                    var total_imported = res.data;

                    if (total_imported == 0) {
                        _this.open_notif(
                            "warning",
                            "Import",
                            "No row to be import"
                        );
                    } else if (total_imported > 0) {
                        _this.open_notif(
                            "success",
                            "Import",
                            "Successfully imported: " + res.data + " row"
                        );
                        _this.getBudget();
                    }
                })
                .catch(function(res) {
                    _this.progressbar_import = false;
                    $(".progress-bar")
                        .css("width", "0%")
                        .attr("aria-valuenow", 0);
                    $(".progress-bar").html("0%");
                    $("#excelcontent").val("");
                    $("#importModal").modal("hide");
                    _this.open_notif(
                        "error",
                        "Message",
                        "FAILURE!! Something went wrong!"
                    );
                });*/
        },
    },
    mounted() {
        //console.log(this.tableData);
        this.getDoctors();

    },
    computed: {
        /*searching() {
            if (!this.search) {
                this.total = this.doctors.length;
                return this.doctors;
            }
            this.page = 1;
            return this.doctors.filter(data => data.name.toLowerCase().includes(this.search.toLowerCase()));
        },
        tableData() {
            this.total = this.searching.length;
            return this.searching.slice(
                this.page_size * this.page - this.page_size,
                this.page_size * this.page
            );
        }*/
    },

}
</script>