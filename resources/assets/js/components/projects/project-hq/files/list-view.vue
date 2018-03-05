<template>
    <div class="tab-pane fade tab-table active in" id="list-view">
        <table>
            <thead>
                <tr>
                    <th> 
                        <div class="table-checkbox">
                            <input type="checkbox" value="None" id="alltype" name="alltype" checked />
                            <label for="alltype"></label>
                        </div>
                    </th>
                    <th> Filetype </th>
                    <th> Filename </th>
                    <th> Uploaded by </th>
                    <th> Project </th>
                    <th>  </th>
                </tr>
            </thead>
            <tbody id="buzz-scroll">
                <tr>
                    <td></td>  
                    <td> <img src="img/files/image.svg"> </td>  
                    <td> <span> Webbanner.jpg </span> </td>  
                    <td> 
                        <div class="user-info">
                            <img src="img/temporary/user1.png">
                        </div>
                        <div class="user-info">
                            <span class="name">Brian Howard</span> <br>
                            <span class="time">uploaded 3hrs ago</span>
                        </div>
                    </td>  
                    <td> <span>XYZ Web Design </span> </td>  
                    <td>
                        
                    </td>  
                </tr>
                <tr>
                    <td></td>  
                    <td> <img src="img/files/documents.svg"> </td>  
                    <td> <span> Questionnaire.doc </span> </td>  
                    <td> 
                        <div class="user-info">
                            <img src="img/temporary/user2.png">
                        </div>
                        <div class="user-info">
                            <span class="name">Brian Howard</span> <br>
                            <span class="time">uploaded 3hrs ago</span>
                        </div>
                    </td>  
                    <td> <span>XYZ Web Design </span> </td>  
                    <td>
                    </td>  
                </tr>
                <tr>
                    <td></td>  
                    <td> <img src="img/files/video.svg"> </td>  
                    <td> <span> Web Intro.mp4 </span> </td>  
                    <td> 
                        <div class="user-info">
                            <img src="img/temporary/user3.png">
                        </div>
                        <div class="user-info">
                            <span class="name">Brian Howard</span> <br>
                            <span class="time">uploaded 3hrs ago</span>
                        </div>
                    </td>  
                    <td> <span>XYZ Web Design </span> </td>  
                    <td>
                    </td>  
                </tr>
                <tr>
                    <td> 
                        <div class="table-checkbox">
                            <input type="checkbox" value="None" id="one" name="one" checked />
                            <label for="one"></label>
                        </div>
                    </td>  
                    <td> <img src="img/files/others.svg"> </td>  
                    <td> <span> Homepage.php </span> </td>  
                    <td> 
                        <div class="user-info">
                            <img src="img/temporary/user2.png">
                        </div>
                        <div class="user-info">
                            <span class="name">Brian Howard</span> <br>
                            <span class="time">uploaded 3hrs ago</span>
                        </div>
                    </td>  
                    <td> <span>XYZ Web Design </span> </td>  
                    <td>
                        <list-option></list-option>
                    </td>  
                </tr>
                <tr>
                    <td></td>  
                    <td> <img src="img/files/image.svg"> </td>  
                    <td> <span> Webbanner.jpg </span> </td>  
                    <td> 
                        <div class="user-info">
                            <img src="img/temporary/user1.png">
                        </div>
                        <div class="user-info">
                            <span class="name">Brian Howard</span> <br>
                            <span class="time">uploaded 3hrs ago</span>
                        </div>
                    </td>  
                    <td> <span>XYZ Web Design </span> </td>  
                    <td>
                        
                    </td>  
                </tr>
                <tr>
                    <td></td>  
                    <td> <img src="img/files/documents.svg"> </td>  
                    <td> <span> Questionnaire.doc </span> </td>  
                    <td> 
                        <div class="user-info">
                            <img src="img/temporary/user2.png">
                        </div>
                        <div class="user-info">
                            <span class="name">Brian Howard</span> <br>
                            <span class="time">uploaded 3hrs ago</span>
                        </div>
                    </td>  
                    <td> <span>XYZ Web Design </span> </td>  
                    <td>
                    </td>  
                </tr>
                <tr>
                    <td></td>  
                    <td> <img src="img/files/video.svg"> </td>  
                    <td> <span> Web Intro.mp4 </span> </td>  
                    <td> 
                        <div class="user-info">
                            <img src="img/temporary/user3.png">
                        </div>
                        <div class="user-info">
                            <span class="name">Brian Howard</span> <br>
                            <span class="time">uploaded 3hrs ago</span>
                        </div>
                    </td>  
                    <td> <span>XYZ Web Design </span> </td>  
                    <td>
                    </td>  
                </tr>
                <tr>
                    <td>
                        <div class="table-checkbox">
                            <input type="checkbox" value="None" id="two" name="two" checked />
                            <label for="two"></label>
                        </div>
                    </td>  
                    <td> <img src="img/files/others.svg"> </td>  
                    <td> <span> Homepage.php </span> </td>  
                    <td> 
                        <div class="user-info">
                            <img src="img/temporary/user2.png">
                        </div>
                        <div class="user-info">
                            <span class="name">Brian Howard</span> <br>
                            <span class="time">uploaded 3hrs ago</span>
                        </div>
                    </td>  
                    <td> <span>XYZ Web Design </span> </td>  
                    <td>
                        <list-option></list-option>
                    </td>  
                </tr>
                <tr>
                    <td></td>  
                    <td> <img src="img/files/image.svg"> </td>  
                    <td> <span> Webbanner.jpg </span> </td>  
                    <td> 
                        <div class="user-info">
                            <img src="img/temporary/user1.png">
                        </div>
                        <div class="user-info">
                            <span class="name">Brian Howard</span> <br>
                            <span class="time">uploaded 3hrs ago</span>
                        </div>
                    </td>  
                    <td> <span>XYZ Web Design </span> </td>  
                    <td>
                        
                    </td>  
                </tr>
                <tr>
                    <td></td>  
                    <td> <img src="img/files/documents.svg"> </td>  
                    <td> <span> Questionnaire.doc </span> </td>  
                    <td> 
                        <div class="user-info">
                            <img src="img/temporary/user2.png">
                        </div>
                        <div class="user-info">
                            <span class="name">Brian Howard</span> <br>
                            <span class="time">uploaded 3hrs ago</span>
                        </div>
                    </td>  
                    <td> <span>XYZ Web Design </span> </td>  
                    <td>
                    </td>  
                </tr>
                <tr>
                    <td></td>  
                    <td> <img src="img/files/video.svg"> </td>  
                    <td> <span> Web Intro.mp4 </span> </td>  
                    <td> 
                        <div class="user-info">
                            <img src="img/temporary/user3.png">
                        </div>
                        <div class="user-info">
                            <span class="name">Brian Howard</span> <br>
                            <span class="time">uploaded 3hrs ago</span>
                        </div>
                    </td>  
                    <td> <span>XYZ Web Design </span> </td>  
                    <td>
                    </td>  
                </tr>
                <tr>
                    <td>
                        <div class="table-checkbox">
                            <input type="checkbox" value="None" id="three" name="three" checked />
                            <label for="three"></label>
                        </div>
                    </td>  
                    <td> <img src="img/files/others.svg"> </td>  
                    <td> <span> Homepage.php </span> </td>  
                    <td> 
                        <div class="user-info">
                            <img src="img/temporary/user2.png">
                        </div>
                        <div class="user-info">
                            <span class="name">Brian Howard</span> <br>
                            <span class="time">uploaded 3hrs ago</span>
                        </div>
                    </td>  
                    <td> <span>XYZ Web Design </span> </td>  
                    <td>
                        <list-option></list-option>
                    </td>  
                </tr>
            </tbody>
        </table>
        <div class="box-footer">
            <a href="#"> View More </a>
        </div>
    </div>
</template>

<script>
    import ListOption from './list-option.vue';

    export default {
        components: {
          'list-option': ListOption,

        },
        data(){
            return {
            }
        }
    }
</script>