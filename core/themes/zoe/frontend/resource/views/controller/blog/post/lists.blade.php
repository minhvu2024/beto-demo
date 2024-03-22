@section("content")
    @php $_option =  array ('refresh' => '1', 'image' =>
                                                         array (
                                                         'resize' => '1',
                                                             'action' => 'php',
                                                             'platforms' =>
                                                                 array (
                                                                 0 => 'mobile',
                                                                 1 => 'tablet',
                                                             ),
                                                             'pc' => '100',
                                                             'mobile' => '55',
                                                             'tablet' => '65',
                                                          )
        );
    @endphp
    <div class="section section-breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Blog</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="section blog-posts-wrapper">
        <div class="container">
            <div class="row">
                <!-- Post -->
                <div class="col-md-4 col-sm-6">
                    <div class="blog-post">

                        <div class="post-info">
                            <div class="post-date">
                                <div class="date">30 JAN, 2013</div>
                            </div>
                            <div class="post-comments-count">
                                <a href="page-blog-post-right-sidebar.html" title="Show Comments"><i
                                            class="glyphicon glyphicon-comment icon-white"></i>11</a>
                            </div>
                        </div>

                        <!-- End Post Info -->
                        <!-- Post Image -->
                        <a href="page-blog-post-right-sidebar.html"><img {!! ZoeImage('/theme/zoe/img/blog1.jpg',$_option)
                    !!} class="post-image" alt="Post Title"></a>
                        <!-- End Post Image -->
                        <!-- Post Title & Summary -->
                        <div class="post-title">
                            <h3><a href="page-blog-post-right-sidebar.html">Post Title</a></h3>
                        </div>
                        <div class="post-summary">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse mattis, nulla id
                                pretium malesuada, dui est laoreet risus, ac rhoncus eros diam id odio. Duis elementum
                                ligula eu ipsum condimentum accumsan.</p>
                        </div>
                        <!-- End Post Title & Summary -->
                        <div class="post-more">
                            <a href="page-blog-post-right-sidebar.html" class="btn btn-small">Read more</a>
                        </div>
                    </div>
                </div>
                <!-- End Post -->
                <div class="col-md-4 col-sm-6">
                    <div class="blog-post">
                        <div class="post-info">
                            <div class="post-date">
                                <div class="date">30 JAN, 2013</div>
                            </div>
                            <div class="post-comments-count">
                                <a href="page-blog-post-right-sidebar.html" title="Show Comments"><i
                                            class="glyphicon glyphicon-comment icon-white"></i>16</a>
                            </div>
                        </div>
                        <a href="page-blog-post-right-sidebar.html"><img
                                    {!! ZoeImage('/theme/zoe/img/blog2.jpg',$_option) !!} class="post-image"
                                    alt="Post Title"></a>
                        <div class="post-title">
                            <h3><a href="page-blog-post-right-sidebar.html">Another Post Title</a></h3>
                        </div>
                        <div class="post-summary">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse mattis, nulla id
                                pretium malesuada, dui est laoreet risus, ac rhoncus eros diam id odio. Duis elementum
                                ligula eu ipsum condimentum accumsan.</p>
                        </div>
                        <div class="post-more">
                            <a href="page-blog-post-right-sidebar.html" class="btn btn-small">Read more</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="blog-post">
                        <div class="post-info">
                            <div class="post-date">
                                <div class="date">30 JAN, 2013</div>
                            </div>
                            <div class="post-comments-count">
                                <a href="page-blog-post-right-sidebar.html" title="Show Comments"><i
                                            class="glyphicon glyphicon-comment icon-white"></i>57</a>
                            </div>
                        </div>
                        <a href="page-blog-post-right-sidebar.html"><img
                                    {!! ZoeImage('/theme/zoe/img/blog3.jpg',$_option) !!} class="post-image"
                                    alt="Post Title"></a>
                        <div class="post-title">
                            <h3><a href="page-blog-post-right-sidebar.html">Yet Another Post Title</a></h3>
                        </div>
                        <div class="post-summary">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse mattis, nulla id
                                pretium malesuada, dui est laoreet risus, ac rhoncus eros diam id odio. Duis elementum
                                ligula eu ipsum condimentum accumsan.</p>
                        </div>
                        <div class="post-more">
                            <a href="page-blog-post-right-sidebar.html" class="btn btn-small">Read more</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="blog-post">
                        <div class="post-info">
                            <div class="post-date">
                                <div class="date">30 JAN, 2013</div>
                            </div>
                            <div class="post-comments-count">
                                <a href="page-blog-post-right-sidebar.html" title="Show Comments"><i
                                            class="glyphicon glyphicon-comment icon-white"></i>11</a>
                            </div>
                        </div>
                        <a href="page-blog-post-right-sidebar.html"><img
                                    {!! ZoeImage('/theme/zoe/img/blog1.jpg',$_option) !!} class="post-image"
                                    alt="Post Title"></a>
                        <div class="post-title">
                            <h3><a href="page-blog-post-right-sidebar.html">Post Title</a></h3>
                        </div>
                        <div class="post-summary">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse mattis, nulla id
                                pretium malesuada, dui est laoreet risus, ac rhoncus eros diam id odio. Duis elementum
                                ligula eu ipsum condimentum accumsan.</p>
                        </div>
                        <div class="post-more">
                            <a href="page-blog-post-right-sidebar.html" class="btn btn-small">Read more</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="blog-post">
                        <div class="post-info">
                            <div class="post-date">
                                <div class="date">30 JAN, 2013</div>
                            </div>
                            <div class="post-comments-count">
                                <a href="page-blog-post-right-sidebar.html" title="Show Comments"><i
                                            class="glyphicon glyphicon-comment icon-white"></i>16</a>
                            </div>
                        </div>
                        <a href="page-blog-post-right-sidebar.html"><img
                                    {!! ZoeImage('/theme/zoe/img/blog2.jpg',$_option) !!} class="post-image"
                                    alt="Post Title"></a>
                        <div class="post-title">
                            <h3><a href="page-blog-post-right-sidebar.html">Another Post Title</a></h3>
                        </div>
                        <div class="post-summary">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse mattis, nulla id
                                pretium malesuada, dui est laoreet risus, ac rhoncus eros diam id odio. Duis elementum
                                ligula eu ipsum condimentum accumsan.</p>
                        </div>
                        <div class="post-more">
                            <a href="page-blog-post-right-sidebar.html" class="btn btn-small">Read more</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="blog-post">
                        <div class="post-info">
                            <div class="post-date">
                                <div class="date">30 JAN, 2013</div>
                            </div>
                            <div class="post-comments-count">
                                <a href="page-blog-post-right-sidebar.html" title="Show Comments"><i
                                            class="glyphicon glyphicon-comment icon-white"></i>57</a>
                            </div>
                        </div>
                        <a href="page-blog-post-right-sidebar.html"><img
                                    {!! ZoeImage('/theme/zoe/img/blog3.jpg',$_option) !!} class="post-image"
                                    alt="Post Title"></a>
                        <div class="post-title">
                            <h3><a href="page-blog-post-right-sidebar.html">Yet Another Post Title</a></h3>
                        </div>
                        <div class="post-summary">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse mattis, nulla id
                                pretium malesuada, dui est laoreet risus, ac rhoncus eros diam id odio. Duis elementum
                                ligula eu ipsum condimentum accumsan.</p>
                        </div>
                        <div class="post-more">
                            <a href="page-blog-post-right-sidebar.html" class="btn btn-small">Read more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection