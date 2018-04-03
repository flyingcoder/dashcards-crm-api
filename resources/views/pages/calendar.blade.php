@extends('layouts.buzzooka')

@section('title', 'Calendar')

@section('content')
    <section class="content buzz-calendar">
        <div class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1> <span class="prev-path"> Dashboard </span> &nbsp; <img src="img/icons/ArrowRight.svg"> &nbsp; <span class="current"> Calendar </span> </h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="head-page-option">
                        <ul class="nav nav-tabs">
                            <add-event></add-event>
                            <li class="sort">
                                 <el-dropdown trigger="click" placement="bottom">
                                    <el-button size="small" class="el-dropdown-link">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="22px" height="6px">
                                            <path fill-rule="evenodd"
                                            d="M19.062,5.250 C17.665,5.250 16.531,4.124 16.531,2.734 C16.531,1.345 17.665,0.219 19.062,0.219 C20.460,0.219 21.594,1.345 21.594,2.734 C21.594,4.124 20.460,5.250 19.062,5.250 ZM10.953,5.250 C9.564,5.250 8.437,4.124 8.437,2.734 C8.437,1.345 9.564,0.219 10.953,0.219 C12.342,0.219 13.469,1.345 13.469,2.734 C13.469,4.124 12.342,5.250 10.953,5.250 ZM2.875,5.250 C1.477,5.250 0.344,4.124 0.344,2.734 C0.344,1.345 1.477,0.219 2.875,0.219 C4.273,0.219 5.406,1.345 5.406,2.734 C5.406,4.124 4.273,5.250 2.875,5.250 Z"/>
                                        </svg>
                                    </el-button>
                                    <el-dropdown-menu slot="dropdown" class="sort-dropdown">
                                        <el-dropdown-item>
                                            <a href="#"> Sort by Events </a>
                                        </el-dropdown-item>
                                        <el-dropdown-item>
                                            <a href="#"> Sort by Date </a>
                                        </el-dropdown-item>
                                    </el-dropdown-menu>  
                                </el-dropdown>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <div class="col-md-4 left-calendar">
                    <div class="row">
                        <div class="col-md-12 calendar-button">
                            <el-button type="primary" plain>Add Event</el-button>
                            <el-button type="primary" plain>Customize Event</el-button>
                        </div>
                    </div>
                    <div class="calendar-content">
                        <div class="today">
                            <div class="image">
                                <img src="img/sidebar/calendar.svg" alt="calendar">
                            </div>
                            <div class="text">
                                <h3> Today </h3>
                                <span> 02 March </span>
                            </div>
                        </div>
                        <div id='calendar'></div>
                        <div class="row">
                            <div class="col-md-6 event-list">
                                <h3> Events </h3> 
                                <ul class="events">
                                    <li> <label class="event-item default"></label> <span>Default</span></li>
                                    <li> <label class="event-item event-1"></label> <span>Ranking Report</span></li>
                                    <li> <label class="event-item event-2"></label> <span>Video Marketing</span></li>
                                    <li> <label class="event-item event-3"></label> <span>Local SEO</span></li>
                                    <li> <label class="event-item event-4"></label> <span>Social Post</span></li>
                                    <li> <label class="event-item event-5"></label> <span>Report Files</span></li>
                                    <li> <label class="event-item event-6"></label> <span>Others</span></li>
                                </ul>
                            </div>
                            <div class="col-md-6 calendar-list">
                                <h3> Calendars </h3> 
                                <ul>
                                <li> <el-checkbox v-model="checked">Reports</el-checkbox> </li>
                                <li> <el-checkbox v-model="checked">Local SEO</el-checkbox> </li>
                                <li> <el-checkbox v-model="checked">Social Posts</el-checkbox> </li>
                                <li> <el-checkbox v-model="checked">Questionners</el-checkbox> </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <events></events> 
            </section>
        </div>
    </section>
@endsection