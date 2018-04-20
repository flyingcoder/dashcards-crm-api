<template>
    <div class="event">
        <h3> March 02, 2018 </h3>
        <div class="event-box event-6">
            <div class="box-head">
                <h2> Event </h2>
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
        <div class="event-box  event-5">
            <div class="box-head">
                <h2> Event </h2>
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
                <div class="event-member more">
                    <li> <img src="img/temporary/user1.png" alt="user"> </li>
                    <li> <img src="img/temporary/user1.png" alt="user"> </li>
                    <li> <img src="img/temporary/user1.png" alt="user"> </li>
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