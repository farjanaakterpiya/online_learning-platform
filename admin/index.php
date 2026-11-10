<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Shikhon – Online Learning Platform</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{--bg:#f4f3ff;--surface:#fff;--surface2:#f1efff;--text:#1d1b3a;--muted:#6f6b92;--line:#e6e3fb;--p:#6d4aff;--p2:#9b7bff;--t:#14c9a6;--a:#ffb020;--r:#ff6b81;--shadow:0 8px 30px rgba(109,74,255,.10);color-scheme:light}
@media (prefers-color-scheme:dark){:root:not([data-theme="light"]){--bg:#0f0d22;--surface:#1a1738;--surface2:#231f4a;--text:#f0eeff;--muted:#a5a0cf;--line:#2c2859;--shadow:0 8px 30px rgba(0,0,0,.35);color-scheme:dark}}
:root[data-theme="dark"]{--bg:#0f0d22;--surface:#1a1738;--surface2:#231f4a;--text:#f0eeff;--muted:#a5a0cf;--line:#2c2859;--shadow:0 8px 30px rgba(0,0,0,.35);color-scheme:dark}
*{box-sizing:border-box;margin:0}
html{scroll-padding-top:env(safe-area-inset-top,0px)}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh;padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px)}
aside{width:250px;background:var(--surface);border-right:1px solid var(--line);padding:22px 16px;position:sticky;top:0;height:100vh;overflow:auto;flex-shrink:0}
.logo{display:flex;align-items:center;gap:10px;font-weight:800;font-size:22px;margin:0 8px 26px}
.logo i{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,var(--p),var(--t));display:grid;place-items:center;color:#fff;font-style:normal}
.mt{font-size:11px;letter-spacing:.1em;color:var(--muted);margin:18px 10px 8px;font-weight:700}
.nav a{display:flex;gap:12px;align-items:center;padding:11px 12px;border-radius:12px;color:var(--muted);text-decoration:none;font-weight:600;font-size:14px;margin-bottom:2px}
.nav a:hover{background:var(--surface2);color:var(--text)}
.nav a.on{background:linear-gradient(135deg,var(--p),var(--p2));color:#fff;box-shadow:0 8px 18px rgba(109,74,255,.35)}
.promo{margin:24px 4px 0;padding:18px;border-radius:18px;background:linear-gradient(135deg,var(--p),var(--t));color:#fff;font-size:13px}
.promo b{display:block;font-size:15px;margin-bottom:6px}
.promo button{margin-top:12px;border:0;background:#fff;color:var(--p);font-weight:700;padding:8px 14px;border-radius:10px;cursor:pointer;font-family:inherit}
main{flex:1;min-width:0;padding:22px 28px}
header{display:flex;align-items:center;gap:14px;margin-bottom:24px;flex-wrap:wrap}
.search{flex:1;min-width:200px;max-width:420px;background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:12px 16px;color:var(--muted);font-size:14px;box-shadow:var(--shadow)}
.sp{flex:1}
.ib{width:42px;height:42px;border-radius:13px;border:1px solid var(--line);background:var(--surface);color:var(--text);cursor:pointer;font-size:17px}
.user{display:flex;align-items:center;gap:10px}
.av{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--a),var(--r));display:grid;place-items:center;color:#fff;font-weight:800}
.user small{color:var(--muted);display:block;font-size:12px}
.hero{border-radius:24px;padding:28px 32px;background:linear-gradient(120deg,var(--p) 0%,#8f6bff 55%,var(--t) 130%);color:#fff;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;box-shadow:0 16px 40px rgba(109,74,255,.3)}
.hero h1{font-size:26px;margin-bottom:6px}.hero p{opacity:.9;font-size:14px;max-width:460px}
.hero button{border:0;background:#fff;color:var(--p);font-weight:700;padding:12px 22px;border-radius:14px;cursor:pointer;font-family:inherit;font-size:14px}
.grid{display:grid;gap:18px;margin-bottom:22px}
.g4{grid-template-columns:repeat(auto-fit,minmax(210px,1fr))}
.g2{grid-template-columns:2fr 1fr}
.g3{grid-template-columns:repeat(auto-fit,minmax(260px,1fr))}
@media(max-width:1000px){.g2{grid-template-columns:1fr}}
@media(max-width:820px){aside{display:none}main{padding:16px}}
.card{background:var(--surface);border:1px solid var(--line);border-radius:20px;padding:20px;box-shadow:var(--shadow)}
.card h3{font-size:16px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center}
.card h3 span{font-size:12px;color:var(--p);font-weight:700}
.stat{display:flex;gap:14px;align-items:center}
.ico{width:50px;height:50px;border-radius:15px;display:grid;place-items:center;font-size:22px}
.stat b{font-size:26px;display:block;line-height:1.1}.stat small{color:var(--muted);font-size:13px}
.up{color:var(--t);font-size:12px;font-weight:700}
.chart{overflow-x:auto}.chart svg{width:100%;min-width:420px;height:auto}
.course{display:flex;gap:14px;align-items:center;padding:12px 0;border-bottom:1px solid var(--line)}
.course:last-child{border:0}
.thumb{width:54px;height:54px;border-radius:15px;display:grid;place-items:center;font-size:24px;flex-shrink:0}
.course div.i{flex:1;min-width:0}.course b{font-size:14px;display:block}.course small{color:var(--muted);font-size:12px}
.bar{height:7px;border-radius:9px;background:var(--surface2);margin-top:8px;overflow:hidden}.bar i{display:block;height:100%;border-radius:9px;background:linear-gradient(90deg,var(--p),var(--t))}
.pct{font-weight:800;font-size:13px;color:var(--p)}
.tw{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:14px;min-width:560px}
th{text-align:left;color:var(--muted);font-size:12px;font-weight:700;padding:10px;border-bottom:1px solid var(--line)}
td{padding:13px 10px;border-bottom:1px solid var(--line)}
.tag{padding:4px 11px;border-radius:20px;font-size:12px;font-weight:700}
.ok{background:rgba(20,201,166,.15);color:var(--t)}.wip{background:rgba(255,176,32,.18);color:#d98a00}.new{background:rgba(109,74,255,.14);color:var(--p)}
.who{display:flex;align-items:center;gap:10px}.who .av{width:34px;height:34px;font-size:13px}
.ev{display:flex;gap:12px;padding:11px 0;align-items:center}
.ev .d{width:46px;text-align:center;border-radius:13px;background:var(--surface2);padding:6px 0;font-weight:800;font-size:16px;line-height:1.1}.ev .d small{display:block;font-size:10px;color:var(--p);letter-spacing:.06em}
.ev b{font-size:14px;display:block}.ev span{font-size:12px;color:var(--muted)}
</style>
</head>
<body>
<aside>
  <div class="logo"><i>🎓</i>Shikhon</div>
  <div class="mt">MAIN</div>
  <nav class="nav">
    <a class="on" href="#dashboard">🏠 Dashboard</a><a href="#courses">📚 My Courses</a><a href="#live">🎥 Live Classes</a><a href="#quiz">📝 Quizzes</a><a href="#cert">🏆 Certificates</a>
  </nav>
  <div class="mt">COMMUNITY</div>
  <nav class="nav"><a href="#mentors">👩‍🏫 Instructors</a><a href="#forum">💬 Discussion</a><a href="#msg">✉️ Messages</a><a href="#settings">⚙️ Settings</a></nav>
  <div class="promo"><b>Go Premium</b>Unlock all courses, mentor support and certificates.<br><button>Upgrade now</button></div>
</aside>
<main>
  <header>
    <div class="search">🔍 Search courses, topics, instructors…</div><div class="sp"></div>
    <button class="ib" id="theme" aria-label="Toggle theme">🌓</button><button class="ib" aria-label="Notifications">🔔</button>
    <div class="user"><div class="av">R</div><div><b style="font-size:14px">Rafi Ahmed</b><small>Student</small></div></div>
  </header>

  <section class="hero">
    <div><h1>Welcome back, Rafi! 👋</h1><p>You’ve completed 72% of your weekly goal. Keep the streak alive — 3 lessons left to finish this week.</p></div>
    <button>Continue learning →</button>
  </section>

  <section class="grid g4">
    <div class="card stat"><div class="ico" style="background:rgba(109,74,255,.14)">📚</div><div><b>12</b><small>Enrolled courses</small><div class="up">▲ 2 this month</div></div></div>
    <div class="card stat"><div class="ico" style="background:rgba(20,201,166,.15)">✅</div><div><b>86</b><small>Lessons completed</small><div class="up">▲ 18%</div></div></div>
    <div class="card stat"><div class="ico" style="background:rgba(255,176,32,.18)">⏱️</div><div><b>142h</b><small>Learning time</small><div class="up">▲ 9%</div></div></div>
    <div class="card stat"><div class="ico" style="background:rgba(255,107,129,.15)">🏆</div><div><b>5</b><small>Certificates</small><div class="up">▲ 1 new</div></div></div>
  </section>

  <section class="grid g2">
    <div class="card"><h3>Learning Activity <span>Last 7 months</span></h3>
      <div class="chart"><svg viewBox="0 0 600 240" role="img" aria-label="Learning hours per month">
        <defs><linearGradient id="g" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#6d4aff" stop-opacity=".35"/><stop offset="1" stop-color="#6d4aff" stop-opacity="0"/></linearGradient></defs>
        <g stroke="currentColor" stroke-opacity=".1"><path d="M40 40H580M40 90H580M40 140H580M40 190H580"/></g>
        <path d="M40 160 C110 140 130 100 200 110 S300 70 360 90 S470 40 520 55 L580 45 L580 200 L40 200Z" fill="url(#g)"/>
        <path d="M40 160 C110 140 130 100 200 110 S300 70 360 90 S470 40 520 55 L580 45" fill="none" stroke="#6d4aff" stroke-width="3.5" stroke-linecap="round"/>
        <path d="M40 180 C110 170 150 150 200 150 S300 130 360 135 S470 110 520 115 L580 100" fill="none" stroke="#14c9a6" stroke-width="3.5" stroke-linecap="round" stroke-dasharray="1 8"/>
        <g fill="currentColor" fill-opacity=".55" font-size="12" font-family="inherit"><text x="36" y="222">Apr</text><text x="125" y="222">May</text><text x="215" y="222">Jun</text><text x="305" y="222">Jul</text><text x="395" y="222">Aug</text><text x="485" y="222">Sep</text><text x="560" y="222">Oct</text></g>
      </svg></div>
    </div>
    <div class="card"><h3>Upcoming <span>See all</span></h3>
      <div class="ev"><div class="d">12<small>NOV</small></div><div><b>Live: React Hooks Deep Dive</b><span>7:00 PM · Sarah Khan</span></div></div>
      <div class="ev"><div class="d">14<small>NOV</small></div><div><b>Quiz: Python Basics</b><span>10:00 AM · 20 questions</span></div></div>
      <div class="ev"><div class="d">16<small>NOV</small></div><div><b>Project Review</b><span>4:30 PM · Mentor session</span></div></div>
      <div class="ev"><div class="d">19<small>NOV</small></div><div><b>Live: UI/UX Workshop</b><span>8:00 PM · Tanvir Hasan</span></div></div>
    </div>
  </section>

  <section class="grid g3">
    <div class="card" style="grid-column:span 2;min-width:0"><h3>Continue Learning <span>View all</span></h3>
      <div class="course"><div class="thumb" style="background:rgba(109,74,255,.14)">⚛️</div><div class="i"><b>Modern React Masterclass</b><small>Sarah Khan · 32 lessons</small><div class="bar"><i style="width:78%"></i></div></div><div class="pct">78%</div></div>
      <div class="course"><div class="thumb" style="background:rgba(20,201,166,.15)">🐍</div><div class="i"><b>Python for Data Science</b><small>Tanvir Hasan · 45 lessons</small><div class="bar"><i style="width:54%"></i></div></div><div class="pct">54%</div></div>
      <div class="course"><div class="thumb" style="background:rgba(255,176,32,.18)">🎨</div><div class="i"><b>UI/UX Design Essentials</b><small>Nusrat Jahan · 24 lessons</small><div class="bar"><i style="width:31%"></i></div></div><div class="pct">31%</div></div>
      <div class="course"><div class="thumb" style="background:rgba(255,107,129,.15)">📈</div><div class="i"><b>Digital Marketing 101</b><small>Imran Hossain · 18 lessons</small><div class="bar"><i style="width:12%"></i></div></div><div class="pct">12%</div></div>
    </div>
    <div class="card"><h3>Top Instructors <span>View all</span></h3>
      <div class="ev"><div class="av" style="background:linear-gradient(135deg,#6d4aff,#9b7bff)">SK</div><div><b>Sarah Khan</b><span>Web Development · ⭐ 4.9</span></div></div>
      <div class="ev"><div class="av" style="background:linear-gradient(135deg,#14c9a6,#6d4aff)">TH</div><div><b>Tanvir Hasan</b><span>Data Science · ⭐ 4.8</span></div></div>
      <div class="ev"><div class="av" style="background:linear-gradient(135deg,#ffb020,#ff6b81)">NJ</div><div><b>Nusrat Jahan</b><span>Design · ⭐ 4.9</span></div></div>
      <div class="ev"><div class="av" style="background:linear-gradient(135deg,#ff6b81,#6d4aff)">IH</div><div><b>Imran Hossain</b><span>Marketing · ⭐ 4.7</span></div></div>
    </div>
  </section>

  <section class="card"><h3>Recent Assignments <span>Export</span></h3>
    <div class="tw"><table>
      <thead><tr><th>Assignment</th><th>Course</th><th>Instructor</th><th>Due</th><th>Status</th><th>Grade</th></tr></thead>
      <tbody>
        <tr><td><b>Build a Todo App</b></td><td>Modern React</td><td><div class="who"><div class="av">SK</div>Sarah Khan</div></td><td>Nov 12</td><td><span class="tag ok">Completed</span></td><td><b>A</b></td></tr>
        <tr><td><b>Pandas Cleaning Task</b></td><td>Python for DS</td><td><div class="who"><div class="av">TH</div>Tanvir Hasan</div></td><td>Nov 15</td><td><span class="tag wip">In progress</span></td><td>—</td></tr>
        <tr><td><b>Mobile Wireframes</b></td><td>UI/UX Design</td><td><div class="who"><div class="av">NJ</div>Nusrat Jahan</div></td><td>Nov 18</td><td><span class="tag new">Not started</span></td><td>—</td></tr>
        <tr><td><b>Campaign Plan</b></td><td>Digital Marketing</td><td><div class="who"><div class="av">IH</div>Imran Hossain</div></td><td>Nov 05</td><td><span class="tag ok">Completed</span></td><td><b>B+</b></td></tr>
      </tbody></table></div>
  </section>
</main>
</body>
</html>
