Hi {{ $user->first_name }},<br />
Thank you for purchasing our course.<br />
Here are some details of the course.<br/>
Course Name: {{ $course->title }}<br />
Location: {{ $course->location }}<br />
Date: {{ $course->online_only ? 'Online' : $course->date_start . ' - ' . $course->date_end }}<br />
Price: ${{ $course->price }}<br />

<br />
Please go ahead and browse the course <a href="{{ url('course/'.$course->slug) }}">here</a><br />
Good luck with your learning.<br />
<br />
Thanks and stay tuned!<br />
Trauma Analytics Team
