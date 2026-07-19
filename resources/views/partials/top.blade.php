<!-- Start Theme Setting Area -->
<button aria-controls="offcanvasScrolling" class="btn btn-primary theme-settings-btn p-0 position-fixed z-2 text-center rounded-circle" data-bs-target="#offcanvasScrolling" data-bs-toggle="offcanvas" style="bottom: 24px; right: 24px; width: 56px; height: 56px; line-height: 54px;" type="button">
    <i class="text-white ri-settings-3-fill fs-28" data-bs-placement="left" data-bs-title="Click On Theme Settings" data-bs-toggle="tooltip">
    </i>
</button>
<!-- Start Theme Setting Area -->
<div aria-labelledby="offcanvasScrollingLabel" class="offcanvas offcanvas-end bg-white border-0" data-bs-backdrop="true" data-bs-scroll="true" id="offcanvasScrolling" style="box-shadow: 0 4px 20px #2f8fe812 !important; max-width: 300px;" tabindex="-1">
    <div class="offcanvas-header bg-light p-20">
        <h5 class="offcanvas-title fs-18 fw-medium" id="offcanvasScrollingLabel">
            Configuration Panel
        </h5>
        <button aria-label="Close" class="btn-close" data-bs-dismiss="offcanvas" type="button">
        </button>
    </div>
    <div class="offcanvas-body p-0 overflow-hidden">
        <div class="last-child-none" data-simplebar="" style="max-height: 858px;">
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    RTL Mode
                </h4>
                <div class="rtl-btn">
                    <label id="switch">
                        <input class="toggle-switch rtl-switch" id="slider" onchange="toggleTheme()" type="checkbox"/>
                    </label>
                </div>
            </div>
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    Only Sidebar Dark
                </h4>
                <div class="sidebar-light-dark" id="sidebar-light-dark">
                    <input class="toggle-switch sidebar-dark-switch" type="checkbox"/>
                </div>
            </div>
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    Only Header Dark
                </h4>
                <div class="header-light-dark" id="header-light-dark">
                    <input class="toggle-switch header-dark-switch" type="checkbox"/>
                </div>
            </div>
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    Right Sidebar
                </h4>
                <div class="right-sidebar" id="right-sidebar">
                    <input class="toggle-switch right-sidebar-switch" type="checkbox"/>
                </div>
            </div>
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    Hide Sidebar
                </h4>
                <div class="icon-sidebar" id="icon-sidebar">
                    <input class="toggle-switch icon-sidebar-switch" type="checkbox"/>
                </div>
            </div>
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    Bordered Card
                </h4>
                <div class="card-border" id="card-border">
                    <input class="toggle-switch border-switch" type="checkbox"/>
                </div>
            </div>
            <div class="p-20 border-bottom child">
                <h4 class="fs-15 fw-medium mb-12">
                    Card Border Radius
                </h4>
                <div class="card-radius-square" id="card-radius-square">
                    <input class="toggle-switch border-radius-switch" type="checkbox"/>
                </div>
            </div>
            <div class="p-20 border-bottom child">
                <a class="btn btn-primary text-white" href="#">
                    Buy StarCode
                </a>
            </div>
        </div>
    </div>
</div>
<!-- End Theme Setting Area -->
