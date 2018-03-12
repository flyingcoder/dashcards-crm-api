<template>
    <section class="content invoice">
        <div class="content-header">
            <page-header></page-header>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <div class="row">
                    <div class="col-md-12 invoice-form">
                        <div class="tab-content">
                            <div class="tab-pane fade tab-table active in" id="form-1">
                                <div class="form-progress">
                                    <div class="form-prog circle active-circle"> </div>
                                    <div class="form-prog progress"> </div>
                                    <div class="form-prog circle"> </div>
                                    <div class="form-prog progress"> </div>
                                    <div class="form-prog circle"> </div>
                                </div>
                                <div class="form-info">
                                    <h1>Additional Info</h1>
                                    <p>This is a dummy text for detailed information about this page</p>
                                </div>
                                <form role="form">
                                    <div class="form-group buzz-group">
                                        <label for="title">
                                            Title
                                        </label>
                                        <input type="text" class="form-control" id="title" />
                                    </div>

                                    <div class="form-group buzz-group">
                                        <label for="description">
                                            Description
                                        </label>
                                        <input type="text" class="form-control" id="description" />
                                    </div>

                                    <div class="form-group buzz-group">
                                        <label for="rate">
                                            Rate
                                        </label>
                                        <input type="text" class="form-control" id="rate" />
                                    </div>

                                    <div class="form-group buzz-group">
                                        <label for="quantity">
                                            Quantity
                                        </label>
                                        <select class="form-control" id="quantity">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                        </select>
                                    </div>

                                    <div class="form-group buzz-form-buttons">
                                        <ul>
                                            <li class="next"> 
                                                <a href="#" data-toggle="tab" @click="filterTasks('my', 'all')"> <span> Previous </span> </a> 
                                            </li>
                                            <li class="prev"> 
                                                <a href="#form-2" data-toggle="tab" @click="filterTasks('my', 'all')"> <span> Next </span> </a> 
                                            </li>
                                            <li class="save"> 
                                                <button type="submit"> <span> Save </span> </button> 
                                            </li>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade tab-table" id="form-2">
                                 <div class="form-progress">
                                    <div class="form-prog circle active-circle"> </div>
                                    <div class="form-prog progress active-progress"> </div>
                                    <div class="form-prog circle active-circle"> </div>
                                    <div class="form-prog progress"> </div>
                                    <div class="form-prog circle"> </div>
                                </div>
                                <div class="form-info">
                                    <h1>Basic Info (1)</h1>
                                    <p>This is a dummy text for detailed information about this page</p>
                                </div>
                                <form role="form">
                                    <div class="form-group buzz-group">
                                        <label for="name">
                                            Name
                                        </label>
                                        <input type="text" class="form-control" id="name" />
                                    </div>

                                    <div class="form-group buzz-group">
                                        <label for="lastname">
                                            Lastname
                                        </label>
                                        <input type="text" class="form-control" id="lastname" />
                                    </div>

                                    <div class="form-group buzz-group">
                                        <label for="address">
                                            Address
                                        </label>
                                        <input type="text" class="form-control" id="address" />
                                    </div>

                                    <div class="form-group buzz-group">
                                        <label for="city">
                                            City
                                        </label>
                                        <input type="text" class="form-control" id="city" />
                                    </div>

                                    <div class="form-group buzz-group">
                                        <label for="state">
                                            State
                                        </label>
                                        <input type="text" class="form-control" id="state" />
                                    </div>

                                    <div class="form-group buzz-group">
                                        <label for="zipcode">
                                            Zip Code
                                        </label>
                                        <input type="text" class="form-control" id="zipcode" />
                                    </div>

                                    <div class="form-group buzz-group">
                                        <label for="phone">
                                            Phone No.
                                        </label>
                                        <input type="text" class="form-control" id="phone" />
                                    </div>

                                    <div class="form-group buzz-group">
                                        <label for="email">
                                            Email
                                        </label>
                                        <input type="email" class="form-control" id="email" />
                                    </div>

                                    <div class="form-group buzz-form-buttons">
                                        <ul>
                                            <li class="next"> 
                                                <a href="#form-1" data-toggle="tab" @click="filterTasks('my', 'all')"> <span> Previous </span> </a> 
                                            </li>
                                            <li class="prev"> 
                                                <a href="#form-3" data-toggle="tab" @click="filterTasks('my', 'all')"> <span> Next </span> </a> 
                                            </li>
                                            <li class="save"> 
                                                <button type="submit"> <span> Save </span> </button> 
                                            </li>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade tab-table" id="form-3"> 
                                 <div class="form-progress">
                                    <div class="form-prog circle active-circle"> </div>
                                    <div class="form-prog progress active-progress"> </div>
                                    <div class="form-prog circle active-circle"> </div>
                                    <div class="form-prog progress active-progress"> </div>
                                    <div class="form-prog circle active-circle"> </div>
                                </div>
                                <div class="form-info">
                                    <h1>Basic Info (2)</h1>
                                    <p>This is a dummy text for detailed information about this page</p>
                                </div>
                                <form role="form">
                                    <div class="form-group buzz-group">
                                        <label for="date-from">
                                            Date Billed
                                        </label>
                                        <input type="text" class="form-control date" id="date-from" />
                                    </div>

                                    <div class="form-group buzz-group">
                                        <label for="date-to">
                                            Date Billed
                                        </label>
                                        <input type="text" class="form-control date" id="date-to" />
                                    </div>

                                    <div class="form-group buzz-group">
                                        <label for="tax">
                                            Tax
                                        </label>
                                        <input type="text" class="form-control" id="tax" />
                                    </div>

                                    <div class="form-group buzz-group">
                                        <label for="note">
                                            Note
                                        </label>
                                        <input type="text" class="form-control" id="note" />
                                    </div>

                                    <div class="form-group buzz-form-buttons">
                                        <ul>
                                            <li class="next"> 
                                                <a href="#form-2" data-toggle="tab" @click="filterTasks('my', 'all')"> <span> Previous </span> </a> 
                                            </li>
                                            <li class="prev"> 
                                                <a href="#" data-toggle="tab" @click="filterTasks('my', 'all')"> <span> Next </span> </a> 
                                            </li>
                                            <li class="save"> 
                                                <button type="submit"> <span> Save </span> </button> 
                                            </li>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</template>

<script>

    export default {   
      data () {
        return {


        }
      },

      mounted () {
        

      },

      methods: {

      }

    }
</script>