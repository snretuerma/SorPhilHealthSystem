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
                    <el-tabs type="border-card" tab-position="bottom">
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
                            :name="item.title+item.name"
                        >
                        {{ item.title }}
                            <el-table
                                :data="item.content"
                                style="width: 100%"
                                height="250">
                                <!--<el-table-column v-for="(ite,index) in item.content" 
                                                :key="item.name"
                                                :prop="item.name"
                                                :label="item.name"> {{ ite[index] }}
                                                
                                </el-table-column>-->
                                <el-table-column
                                fixed
                                prop="erewr"
                                label="Date"
                                width="150">
                                </el-table-column>
                                <el-table-column
                                prop="1"
                                label="Name"
                                width="120">
                                </el-table-column>
                                <el-table-column
                                prop="2"
                                label="State"
                                width="120">
                                </el-table-column>
                                <el-table-column
                                prop="3"
                                label="City"
                                width="120">
                                </el-table-column>
                                <el-table-column
                                prop="4"
                                label="Address"
                                width="300">
                                </el-table-column>
                                <el-table-column
                                prop="5"
                                label="Zip"
                                width="120">
                                </el-table-column>
                            </el-table>
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
        };
    },
    methods: {
        
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

                //console.log(e.target.files[0]);
                var reader = new FileReader();
                reader.readAsArrayBuffer(file.raw);
                reader.onloadend = function(e) {
                    //console.log(e);
                        var data = new Uint8Array(reader.result);
                        var wb = XLSX.read(data,{type:'array'});
                       //console.log("console me");
                        //console.log(wb.SheetNames.length);
                        //var htmlstr = XLSX.write(wb,{sheet:"sheet no1", type:'binary',bookType:'html'});
                        //$('#wrapper')[0].innerHTML += htmlstr;
                        //console.log(data);

                         _this.sheet_length = wb.SheetNames.length;
                         for (let i = 0; i < wb.SheetNames.length; i++) {
                             //const element = array[i];
                                let sheetName = wb.SheetNames[i];
                                let worksheet = wb.Sheets[sheetName];
                               // console.log(sheetName);

                               
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
                                    content: XLSX.utils.sheet_to_json(worksheet)
                                });

                                var rowObj =XLSX.utils.sheet_to_json(wb.Sheets[sheetName]);
                                console.log(JSON.stringify(rowObj));

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
                         }

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
        }
    },
    mounted() {
    },

}
</script>