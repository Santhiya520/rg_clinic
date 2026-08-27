<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\Gallery;
use App\Models\Notice;
use App\Models\Review;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Team;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        $sliders = Slider::ordered()->get();
        $galleries = Gallery::ordered()->get();
        $services = Service::ordered()->active()->get();
        $teamMembers = Team::ordered()->active()->get();
        $reviews = Review::ordered()->active()->get();
        $notice = Notice::first();
        $result = [
            'sliders' => $sliders,
            'galleries' => $galleries,
            'services' => $services,
            'teamMembers' => $teamMembers,
            'reviews' => $reviews,
            'notice' => $notice,
        ];
        return view('front.home', $result);
    }

    /**
     * Display about page
     */
    public function about()
    {
        $teamMembers = Team::ordered()->active()->get();
        $reviews = Review::ordered()->active()->get();
        $result = [
            'teamMembers' => $teamMembers,
            'reviews' => $reviews,
        ];
        return view('front.about', $result);
    }

    /**
     * Display services page
     */
    public function services()
    {
        $services = Service::ordered()->active()->get();
        $result = [
            'services' => $services,
        ];
        return view('front.services', $result);
    }

    /**
     * Display donar page
     */
    public function donar()
    {
        $donors = Donor::ordered()->active()->get();
        $result = [
            'donors' => $donors,
        ];
        return view('front.donar', $result);
    }

    /**
     * Display gallery page
     */
    public function gallery()
    {
        $galleries = Gallery::ordered()->get();
        $result = [
            'galleries' => $galleries,
        ];
        return view('front.gallery', $result);
    }

    /**
     * Display therapists page
     */
    public function therapists()
    {
        return view('front.therapists');
    }

    /**
     * Display therapist details page
     */
    public function therapistDetails()
    {
        return view('front.therapist-details');
    }

    /**
     * Display blog page
     */
    public function blog()
    {
        return view('front.blog');
    }

    /**
     * Display blog details page
     */
    public function blogDetails()
    {
        return view('front.blog-details');
    }

    /**
     * Display testimonials page
     */
    public function testimonials()
    {
        return view('front.testimonials');
    }

    /**
     * Display FAQs page
     */
    public function faqs()
    {
        return view('front.faqs');
    }

    /**
     * Display appointment page
     */
    public function appointment()
    {
        return view('front.appointment');
    }

    /**
     * Display service details page
     */
    public function serviceDetails()
    {
        return view('front.service-details');
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        return view('front.contact');
    }

    /**
     * Display 404 page
     */
    public function notFound()
    {
        return view('front.404');
    }
}
