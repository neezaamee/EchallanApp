@extends('layout.cms-layout')
@section('page-title', 'Profile - ')
@section('cms-main-content')
<livewire:circles.edit-circle :id="$id" />
    {{-- <div class="row">
        <div class="col-12">
            <div class="card mb-3 btn-reveal-trigger">
                <div class="card-header position-relative min-vh-25 mb-8">
                    <div class="cover-image">
                        <div class="bg-holder rounded-3 rounded-bottom-0"
                            style="background-image:url(../../assets/img/generic/4.jpg);">
                        </div>
                        <!--/.bg-holder-->

                        <input class="d-none" id="upload-cover-image" type="file" />
                        <label class="cover-image-file-input" for="upload-cover-image"><span
                                class="fas fa-camera me-2"></span><span>Change cover photo</span></label>
                    </div>
                    <div class="avatar avatar-5xl avatar-profile shadow-sm img-thumbnail rounded-circle">
                        <div class="h-100 w-100 rounded-circle overflow-hidden position-relative"> <img
                                src="../../assets/img/team/2.jpg" width="200" alt=""
                                data-dz-thumbnail="data-dz-thumbnail" />
                            <input class="d-none" id="profile-image" type="file" />
                            <label class="mb-0 overlay-icon d-flex flex-center" for="profile-image"><span
                                    class="bg-holder overlay overlay-0"></span><span
                                    class="z-1 text-white dark__text-white text-center fs-10"><span
                                        class="fas fa-camera"></span><span class="d-block">Update</span></span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
            <div class="card-header">
              <div class="row">
                <div class="col">
                  <h5 class="mb-2">Tony Robbins (<a href="mailto:tony@gmail.com">tony@gmail.com</a>)</h5><a class="btn btn-falcon-default btn-sm" href="#!"><svg class="svg-inline--fa fa-plus fa-w-14 fs-11 me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg><!-- <span class="fas fa-plus fs-11 me-1"></span> Font Awesome fontawesome.com -->Add note</a>
                  <button class="btn btn-falcon-default btn-sm dropdown-toggle ms-2 dropdown-caret-none" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg class="svg-inline--fa fa-ellipsis-h fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-h" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z"></path></svg><!-- <span class="fas fa-ellipsis-h"></span> Font Awesome fontawesome.com --></button>
                  <div class="dropdown-menu" style=""><a class="dropdown-item" href="#">Edit</a><a class="dropdown-item" href="#">Report</a><a class="dropdown-item" href="#">Archive</a>
                    <div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="#">Delete user</a>
                  </div>
                </div>
                <div class="col-auto d-none d-sm-block">
                  <h6 class="text-uppercase text-600">Customer<svg class="svg-inline--fa fa-user fa-w-14 ms-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"></path></svg><!-- <span class="fas fa-user ms-2"></span> Font Awesome fontawesome.com --></h6>
                </div>
              </div>
            </div>
            <div class="card-body border-top">
              <div class="d-flex"><svg class="svg-inline--fa fa-user fa-w-14 text-success me-2" data-fa-transform="down-5" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.4375em 0.8125em;"><g transform="translate(224 256)"><g transform="translate(0, 160)  scale(1, 1)  rotate(0 0 0)"><path fill="currentColor" d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z" transform="translate(-224 -256)"></path></g></g></svg><!-- <span class="fas fa-user text-success me-2" data-fa-transform="down-5"></span> Font Awesome fontawesome.com -->
                <div class="flex-1">
                  <p class="mb-0">Customer was created</p>
                  <p class="fs-10 mb-0 text-600">Jan 12, 11:13 PM</p>
                </div>
              </div>
            </div>
          </div>
        </div>

    </div>
    <div class="row g-0">
        <div class="col-lg-8 pe-lg-2">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Profile Settings</h5>
                </div>
                <div class="card-body bg-body-tertiary">
                    <form class="row g-3">
                        <div class="col-lg-6">
                            <label class="form-label" for="first-name">First Name</label>
                            <input class="form-control" id="first-name" type="text" value="Ali" />
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="last-name">Last Name</label>
                            <input class="form-control" id="last-name" type="text" value="Nizami" />
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="cnic">CNIC</label>
                            <input class="form-control" id="cnic" type="text" value="3310221557297" />
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control" id="email" type="text" value="neezaamee@gmail.com" />
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="last-name">Phone</label>
                            <input class="form-control" id="last-name" type="text" value="+923022211000" />
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="email1">Rank</label>
                            <input class="form-control" id="email1" type="text" value="DEO" />
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="email2">Designation</label>
                            <input class="form-control" id="email2" type="text" value="Admin" />
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="email2">Posting</label>
                            <input class="form-control" id="email2" type="text" value="CTO Office" />
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Update </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Experiences</h5>
                </div>
                <div class="card-body bg-body-tertiary"><a class="mb-4 d-block d-flex align-items-center"
                        href="#experience-form1" data-bs-toggle="collapse" aria-expanded="false"
                        aria-controls="experience-form1"><span class="circle-dashed"><span
                                class="fas fa-plus"></span></span><span class="ms-3">Add new experience</span></a>
                    <div class="collapse" id="experience-form1">
                        <form class="row">
                            <div class="col-3 mb-3 text-lg-end">
                                <label class="form-label" for="company">Company</label>
                            </div>
                            <div class="col-9 col-sm-7 mb-3">
                                <input class="form-control form-control-sm" id="company" type="text" />
                            </div>
                            <div class="col-3 mb-3 text-lg-end">
                                <label class="form-label" for="position">Position</label>
                            </div>
                            <div class="col-9 col-sm-7 mb-3">
                                <input class="form-control form-control-sm" id="position" type="text" />
                            </div>
                            <div class="col-3 mb-3 text-lg-end">
                                <label class="form-label" for="city">City </label>
                            </div>
                            <div class="col-9 col-sm-7 mb-3">
                                <input class="form-control form-control-sm" id="city" type="text" />
                            </div>
                            <div class="col-3 mb-3 text-lg-end">
                                <label class="form-label" for="exp-description">Description </label>
                            </div>
                            <div class="col-9 col-sm-7 mb-3">
                                <textarea class="form-control form-control-sm" id="exp-description" rows="3"> </textarea>
                            </div>
                            <div class="col-9 col-sm-7 offset-3 mb-3">
                                <div class="form-check mb-0 lh-1">
                                    <input class="form-check-input" type="checkbox" id="experience-current"
                                        checked="checked" />
                                    <label class="form-check-label mb-0" for="experience-current">I currently work here
                                    </label>
                                </div>
                            </div>
                            <div class="col-3 text-lg-end">
                                <label class="form-label" for="experience-form2">From </label>
                            </div>
                            <div class="col-9 col-sm-7 mb-3">
                                <input class="form-control form-control-sm text-500 datetimepicker" id="experience-form2"
                                    type="text" placeholder="dd/mm/yy"
                                    data-options='{"dateFormat":"d/m/y","disableMobile":true}' />
                            </div>
                            <div class="col-3 text-lg-end">
                                <label class="form-label" for="experience-to">To </label>
                            </div>
                            <div class="col-9 col-sm-7 mb-3">
                                <input class="form-control form-control-sm text-500 datetimepicker" id="experience-to"
                                    type="text" placeholder="dd/mm/yy"
                                    data-options='{"dateFormat":"d/m/y","disableMobile":true}' />
                            </div>
                            <div class="col-9 col-sm-7 offset-3">
                                <button class="btn btn-primary" type="button">Save</button>
                            </div>
                        </form>
                        <div class="border-dashed-bottom my-4"></div>
                    </div>
                    <div class="d-flex"><a href="#!"> <img class="img-fluid" src="../../assets/img/logos/g.png"
                                alt="" width="56" /></a>
                        <div class="flex-1 position-relative ps-3">
                            <h6 class="fs-9 mb-0">Big Data Engineer<span data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Verified"><small class="fa fa-check-circle text-primary"
                                        data-fa-transform="shrink-4 down-2"></small></span>
                            </h6>
                            <p class="mb-1"> <a href="#!">Google</a></p>
                            <p class="text-1000 mb-0">Apr 2012 - Present &bull; 6 yrs 9 mos</p>
                            <p class="text-1000 mb-0">California, USA</p>
                            <div class="border-bottom border-dashed my-3"></div>
                        </div>
                    </div>
                    <div class="d-flex"><a href="#!"> <img class="img-fluid" src="../../assets/img/logos/apple.png"
                                alt="" width="56" /></a>
                        <div class="flex-1 position-relative ps-3">
                            <h6 class="fs-9 mb-0">Software Engineer<span data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Verified"><small class="fa fa-check-circle text-primary"
                                        data-fa-transform="shrink-4 down-2"></small></span>
                            </h6>
                            <p class="mb-1"> <a href="#!">Apple</a></p>
                            <p class="text-1000 mb-0">Jan 2012 - Apr 2012 &bull; 4 mos</p>
                            <p class="text-1000 mb-0">California, USA</p>
                            <div class="border-bottom border-dashed my-3"></div>
                        </div>
                    </div>
                    <div class="d-flex"><a href="#!"> <img class="img-fluid" src="../../assets/img/logos/nike.png"
                                alt="" width="56" /></a>
                        <div class="flex-1 position-relative ps-3">
                            <h6 class="fs-9 mb-0">Mobile App Developer<span data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Verified"><small
                                        class="fa fa-check-circle text-primary"
                                        data-fa-transform="shrink-4 down-2"></small></span>
                            </h6>
                            <p class="mb-1"> <a href="#!">Nike</a></p>
                            <p class="text-1000 mb-0">Jan 2011 - Apr 2012 &bull; 1 yr 4 mos</p>
                            <p class="text-1000 mb-0">Beaverton, USA</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3 mb-lg-0">
                <div class="card-header">
                    <h5 class="mb-0">Educations</h5>
                </div>
                <div class="card-body bg-body-tertiary"><a class="mb-4 d-block d-flex align-items-center"
                        href="#education-form" data-bs-toggle="collapse" aria-expanded="false"
                        aria-controls="education-form"><span class="circle-dashed"><span
                                class="fas fa-plus"></span></span><span class="ms-3">Add new education</span></a>
                    <div class="collapse" id="education-form">
                        <form class="row">
                            <div class="col-3 mb-3 text-lg-end">
                                <label class="form-label" for="school">School</label>
                            </div>
                            <div class="col-9 col-sm-7 mb-3">
                                <input class="form-control form-control-sm" id="school" type="text" />
                            </div>
                            <div class="col-3 mb-3 text-lg-end">
                                <label class="form-label" for="degree">Degree</label>
                            </div>
                            <div class="col-9 col-sm-7 mb-3">
                                <input class="form-control form-control-sm" id="degree" type="text" />
                            </div>
                            <div class="col-3 mb-3 text-lg-end">
                                <label class="form-label" for="field">Field</label>
                            </div>
                            <div class="col-9 col-sm-7 mb-3">
                                <input class="form-control form-control-sm" id="field" type="text" />
                            </div>
                            <div class="col-3 text-lg-end">
                                <label class="form-label" for="edu-form3">From </label>
                            </div>
                            <div class="col-9 col-sm-7 mb-3">
                                <input class="form-control form-control-sm datetimepicker" id="edu-form3" type="text"
                                    placeholder="dd/mm/yy" data-options='{"dateFormat":"d/m/y"}' />
                            </div>
                            <div class="col-3 text-lg-end">
                                <label class="form-label" for="edu-to">To </label>
                            </div>
                            <div class="col-9 col-sm-7 mb-3">
                                <input class="form-control form-control-sm datetimepicker" id="edu-to" type="text"
                                    placeholder="dd/mm/yy" data-options='{"dateFormat":"d/m/y"}' />
                            </div>
                            <div class="col-9 col-sm-7 offset-3">
                                <button class="btn btn-primary" type="button">Save</button>
                            </div>
                        </form>
                        <div class="border-dashed-bottom my-3"></div>
                    </div>
                    <div class="d-flex"><a href="#!">
                            <div class="avatar avatar-3xl">
                                <div class="avatar-name rounded-circle"><span>SU</span></div>
                            </div>
                        </a>
                        <div class="flex-1 position-relative ps-3">
                            <h6 class="fs-9 mb-0"> <a href="#!">Stanford University<span data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Verified"><small
                                            class="fa fa-check-circle text-primary"
                                            data-fa-transform="shrink-4 down-2"></small></span></a></h6>
                            <p class="mb-1">Computer Science and Engineering</p>
                            <p class="text-1000 mb-0">2010 - 2014 • 4 yrs</p>
                            <p class="text-1000 mb-0">California, USA</p>
                            <div class="border-bottom border-dashed my-3"></div>
                        </div>
                    </div>
                    <div class="d-flex"><a href="#!"> <img class="img-fluid"
                                src="../../assets/img/logos/staten.png" alt="" width="56" /></a>
                        <div class="flex-1 position-relative ps-3">
                            <h6 class="fs-9 mb-0"> <a href="#!">Staten Island Technical High School<span
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Verified"><small
                                            class="fa fa-check-circle text-primary"
                                            data-fa-transform="shrink-4 down-2"></small></span></a></h6>
                            <p class="mb-1">Higher Secondary School Certificate, Science</p>
                            <p class="text-1000 mb-0">2008 - 2010 &bull; 2 yrs</p>
                            <p class="text-1000 mb-0">New York, USA</p>
                            <div class="border-bottom border-dashed my-3"></div>
                        </div>
                    </div>
                    <div class="d-flex"><a href="#!"> <img class="img-fluid"
                                src="../../assets/img/logos/tj-heigh-school.png" alt="" width="56" /></a>
                        <div class="flex-1 position-relative ps-3">
                            <h6 class="fs-9 mb-0"> <a href="#!">Thomas Jefferson High School for Science and
                                    Technology<span data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Verified"><small class="fa fa-check-circle text-primary"
                                            data-fa-transform="shrink-4 down-2"></small></span></a></h6>
                            <p class="mb-1">Secondary School Certificate, Science</p>
                            <p class="text-1000 mb-0">2003 - 2008 &bull; 5 yrs</p>
                            <p class="text-1000 mb-0">Alexandria, USA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 ps-lg-2">
            <div class="sticky-sidebar">
                <div class="card mb-3 overflow-hidden">
                    <div class="card-header">
                        <h5 class="mb-0">Account Settings</h5>
                    </div>
                    <div class="card-body bg-body-tertiary">
                        <h6 class="fw-bold">Who can see your profile ?<span class="fs-11 ms-1 text-primary"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Only The group of selected people can see your profile"><span
                                    class="fas fa-question-circle"></span></span></h6>
                        <div class="ps-2">
                            <div class="form-check mb-0 lh-1">
                                <input class="form-check-input" type="radio" value="" id="everyone"
                                    name="view-settings" />
                                <label class="form-check-label mb-0" for="everyone">Everyone
                                </label>
                            </div>
                            <div class="form-check mb-0 lh-1">
                                <input class="form-check-input" type="radio" value="" id="my-followers"
                                    checked="checked" name="view-settings" />
                                <label class="form-check-label mb-0" for="my-followers">My followers
                                </label>
                            </div>
                            <div class="form-check mb-0 lh-1">
                                <input class="form-check-input" type="radio" value="" id="only-me"
                                    name="view-settings" />
                                <label class="form-check-label mb-0" for="only-me">Only me
                                </label>
                            </div>
                        </div>
                        <h6 class="mt-2 fw-bold">Who can tag you ?<span class="fs-11 ms-1 text-primary"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Only The group of selected people can tag you"><span
                                    class="fas fa-question-circle"></span></span></h6>
                        <div class="ps-2">
                            <div class="form-check mb-0 lh-1">
                                <input class="form-check-input" type="radio" value="" id="tag-everyone"
                                    name="tag-settings" />
                                <label class="form-check-label mb-0" for="tag-everyone">Everyone
                                </label>
                            </div>
                            <div class="form-check mb-0 lh-1">
                                <input class="form-check-input" type="radio" value="" id="group-members"
                                    checked="checked" name="tag-settings" />
                                <label class="form-check-label mb-0" for="group-members">Group Members
                                </label>
                            </div>
                        </div>
                        <div class="border-dashed-bottom my-3"></div>
                        <div class="form-check mb-0 lh-1">
                            <input class="form-check-input" type="checkbox" id="userSettings1" checked="checked" />
                            <label class="form-check-label mb-0" for="userSettings1">Allow users to show your followers
                            </label>
                        </div>
                        <div class="form-check mb-0 lh-1">
                            <input class="form-check-input" type="checkbox" id="userSettings2" checked="checked" />
                            <label class="form-check-label mb-0" for="userSettings2">Allow users to show your email
                            </label>
                        </div>
                        <div class="form-check mb-0 lh-1">
                            <input class="form-check-input" type="checkbox" id="userSettings3" />
                            <label class="form-check-label mb-0" for="userSettings3">Allow users to show your experiences
                            </label>
                        </div>
                        <div class="border-bottom border-dashed my-3"></div>
                        <div class="form-check form-switch mb-0 lh-1">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                                checked="checked" />
                            <label class="form-check-label mb-0" for="flexSwitchCheckDefault">Make your phone number
                                visible
                            </label>
                        </div>
                        <div class="form-check form-switch mb-0 lh-1">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" />
                            <label class="form-check-label mb-0" for="flexSwitchCheckChecked">Allow user to follow you
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Billing Setting</h5>
                    </div>
                    <div class="card-body bg-body-tertiary">
                        <h5>Plan</h5>
                        <p class="fs-9"><strong>Developer</strong>- Unlimited private repositories</p><a
                            class="btn btn-falcon-default btn-sm" href="#!">Update Plan</a>
                    </div>
                    <div class="card-body bg-body-tertiary border-top">
                        <h5>Payment</h5>
                        <p class="fs-9">You have not added any payment.</p><a class="btn btn-falcon-default btn-sm"
                            href="#!">Add Payment </a>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Change Password</h5>
                    </div>
                    <div class="card-body bg-body-tertiary">
                        <form>
                            <div class="mb-3">
                                <label class="form-label" for="old-password">Old Password</label>
                                <input class="form-control" id="old-password" type="password" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="new-password">New Password</label>
                                <input class="form-control" id="new-password" type="password" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="confirm-password">Confirm Password</label>
                                <input class="form-control" id="confirm-password" type="password" />
                            </div>
                            <button class="btn btn-primary d-block w-100" type="submit">Update Password </button>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Danger Zone</h5>
                    </div>
                    <div class="card-body bg-body-tertiary">
                        <h5 class="fs-9">Transfer Ownership</h5>
                        <p class="fs-10">Transfer this account to another user or to an organization where you have the
                            ability to create repositories.</p><a class="btn btn-falcon-warning d-block"
                            href="#!">Transfer</a>
                        <div class="border-bottom border-dashed my-4"></div>
                        <h5 class="fs-9">Delete this account</h5>
                        <p class="fs-10">Once you delete a account, there is no going back. Please be certain.</p><a
                            class="btn btn-falcon-danger d-block" href="#!">Deactivate Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
