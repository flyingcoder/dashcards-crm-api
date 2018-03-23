
<template>
    <el-table :data="tableData" style="width: 100%">
        <el-table-column label="Name" width="180">
            <template slot-scope="scope">
                <span>{{ scope.row.name }}</span>
            </template>
        </el-table-column>
        <el-table-column label="Support" width="100">
            <template slot-scope="scope">
                <span>{{ scope.row.support }}</span>
            </template>
        </el-table-column>
        <el-table-column label="Actions">
            <template slot-scope="scope">
                <el-button class="action-move">
                    <img src="img/action/move.svg" width="26px" height="26px">
                    Move Tickets
                </el-button>
                <el-popover trigger="hover" placement="top">
                    <p class="popup-text-department">Do you really want to delete <br> Department?</p>
                    <div class="popup-deparment-button">
                        <el-button type="text" @click="cancelDelete = false">No</el-button>
                        <el-button type="primary" @click="confirmDelete = false">Yes</el-button>
                    </div>
                    <el-button slot="reference" class="action-delete">
                        <img src="img/action/delete.svg" width="17px" height="23px">
                        Delete Department 
                    </el-button>
                </el-popover>
                <el-button @click="handleDelete(scope.$index, scope.row)" class="action-delete">
                    <img src="img/action/delete.svg" width="17px" height="23px">
                    Delete Tickets 
                </el-button>
                <el-button @click="handleEdit(scope.$index, scope.row)" class="action-edit">
                    <img src="img/action/edit.svg" width="20px" height="19px">
                    Edit Settings 
                </el-button>
            </template>
        </el-table-column>
    </el-table>
</template>

<script>
    export default {
        data() {
            return {
                confirmDelete: false,
                tableData: [
                    {
                    name: 'General Support',
                    support: '0',
                    }, 
                    {
                    name: 'Support 02',
                    support: '5',
                    }, 
                    {
                    name: 'Support 03',
                    support: '3',
                    }, 
                ]
            }
        },
        methods: {
        handleEdit(index, row) {
            console.log(index, row);
        },
        handleDelete(index, row) {
            console.log(index, row);
        }
        }
    }
</script>