import Link from "next/link";
import { requireAdmin } from "@/lib/admin/auth";
import { createServiceClient } from "@/lib/supabase/server";
import { AdminBtn, AdminPageHeader } from "@/components/admin/AdminUi";

async function count(table: string) {
  const supabase = createServiceClient();
  const { count } = await supabase.from(table).select("*", { count: "exact", head: true });
  return count ?? 0;
}

function formatCount(value: number) {
  return value.toLocaleString("en-MY");
}

export default async function AdminDashboardPage() {
  await requireAdmin();
  const [users, apps, visits, counsellors, enquiries, universities, courses, blogs, scholarships, careers] =
    await Promise.all([
      count("app_users"),
      count("course_application"),
      count("visit_campus"),
      count("consult_counsellor"),
      count("enquiries"),
      count("universities"),
      count("courses"),
      count("blogs"),
      count("scholarships"),
      count("careers"),
    ]);

  const stats = [
    { href: "/admin/users", title: "Users", value: users },
    { href: "/admin/university", title: "Universities", value: universities },
    { href: "/admin/course", title: "Courses", value: courses },
    { href: "/admin/scholarship", title: "Scholarships", value: scholarships },
    { href: "/admin/blog", title: "Blogs", value: blogs },
    { href: "/admin/job", title: "Careers", value: careers },
  ];

  const inbound = [
    { href: "/admin/courseapplication", label: "Course applications", value: apps },
    { href: "/admin/counsellor", label: "Counsellor requests", value: counsellors },
    { href: "/admin/visitcampus", label: "Campus visits", value: visits },
    { href: "/admin/enquiries", label: "Enquiries", value: enquiries },
  ];

  const manage = [
    { href: "/admin/university", label: "Universities" },
    { href: "/admin/course", label: "Courses" },
    { href: "/admin/scholarship", label: "Scholarships" },
    { href: "/admin/blog", label: "Blog" },
    { href: "/admin/k-12", label: "K-12 schools" },
    { href: "/admin/users", label: "Users" },
  ];

  return (
    <>
      <AdminPageHeader
        title="Account home"
        description="Live counts, inbound requests, and shortcuts for day-to-day work."
        actions={
          <div className="admin-actions">
            <AdminBtn href="/admin/university/new">Add university</AdminBtn>
            <AdminBtn href="/admin/course/new" variant="secondary">Add course</AdminBtn>
            <AdminBtn href="/admin/scholarship/new" variant="secondary">Add scholarship</AdminBtn>
          </div>
        }
      />

      <section className="admin-home-section">
        <div className="admin-home-section-head">
          <h2>Catalogue</h2>
          <p>Published records across the main modules.</p>
        </div>
        <div className="admin-stat-grid">
          {stats.map((s) => (
            <Link key={s.href} href={s.href} className="admin-stat-card">
              <div className="label">{s.title}</div>
              <div className="value">{formatCount(s.value)}</div>
            </Link>
          ))}
        </div>
      </section>

      <div className="admin-home-split">
        <section className="admin-home-panel">
          <div className="admin-home-section-head">
            <h2>Inbound</h2>
            <p>Open queues waiting for a response.</p>
          </div>
          <ul className="admin-home-list">
            {inbound.map((item) => (
              <li key={item.href}>
                <Link href={item.href}>
                  <span>{item.label}</span>
                  <strong>{formatCount(item.value)}</strong>
                </Link>
              </li>
            ))}
          </ul>
        </section>

        <section className="admin-home-panel">
          <div className="admin-home-section-head">
            <h2>Browse</h2>
            <p>Jump into the modules you edit most.</p>
          </div>
          <ul className="admin-home-list">
            {manage.map((item) => (
              <li key={item.href}>
                <Link href={item.href}>
                  <span>{item.label}</span>
                  <strong aria-hidden>→</strong>
                </Link>
              </li>
            ))}
          </ul>
        </section>
      </div>
    </>
  );
}
