<template>
    <div class="event ">
        <h3> All Events </h3>
        <div class="event-box event-1">
            <div class="box-head">
                <h2> March 02 </h2>
                <grid-option></grid-option>
            </div>
            <div class="box-content">
                <div class="event-item"></div>
                <div class="event-time">
                    12:00 PM - 1:30 PM
                </div>
                <div class="event-name">
                    Video Product Roadmap 02
                </div>
                <div class="event-member">
                    <li> <img src="img/temporary/user1.png" alt="user"> </li>
                </div>
                <el-button class="join">
                    Join Meeting
                </el-button>
            </div>
            <div class="box-footer">
                <a href=""> View More </a>
            </div>
        </div>
        <div class="event-box event-4">
            <div class="box-head">
                <h2> March 07 </h2>
                <grid-option></grid-option>
            </div>
            <div class="box-content">
                <div class="event-item"></div>
                <div class="event-time">
                    12:00 PM - 1:30 PM
                </div>
                <div class="event-name">
                    Social Media Post
                </div>
                <div class="event-member">
                    <li> <img src="img/temporary/user1.png" alt="user"> </li>
                    <li> <img src="img/temporary/user2.png" alt="user"> </li>
                </div>
                <el-button class="join">
                    Join Meeting
                </el-button>
            </div>
            <div class="box-footer">
                <a href=""> View More </a>
            </div>
        </div>
        <div class="event-box event-2">
            <div class="box-head">
                <h2> March 13 </h2>
                <grid-option></grid-option>
            </div>
            <div class="box-content">
                <div class="event-item"></div>
                <div class="event-time">
                    12:00 PM - 1:30 PM
                </div>
                <div class="event-name">
                    Ranking Report
                </div>
                <div class="event-member">
                    <li> <img src="img/temporary/user1.png" alt="user"> </li>
                    <li> <img src="img/temporary/user3.png" alt="user"> </li>
                    <li> <img src="img/temporary/user2.png" alt="user"> </li>
                </div>
                <el-button class="join">
                    Join Meeting
                </el-button>
            </div>
            <div class="box-footer">
                <a href=""> View More </a>
            </div>
        </div>
        <div class="event-box event-3">
            <div class="box-head">
                <h2> March 20 </h2>
                <grid-option></grid-option>
            </div>
            <div class="box-content">
                <div class="event-item"></div>
                <div class="event-time">
                    12:00 PM - 1:30 PM
                </div>
                <div class="event-name">
                    Video Product Review
                </div>
                <div class="event-member more">
                    <li> <img src="img/temporary/user1.png" alt="user"> </li>
                    <li> <img src="img/temporary/user3.png" alt="user"> </li>
                    <li> <img src="img/temporary/user2.png" alt="user"> </li>
                    <li> <img src="img/temporary/user2.png" alt="user"> </li>
                    <span> +2 more invited </span>
                </div>
                <el-button class="join">
                    Join Meeting
                </el-button>
            </div>
            <div class="box-footer">
                <a href=""> View More </a>
            </div>
        </div>
    </div>
</template>

<script>
    import GridOption from './GridOption.vue';

    export default {
        components: {
          'grid-option': GridOption,
      },
    }
</script>