# Cora Builder - Development Progress

## 🟢 Project Status
**Current Version:** 1.0.0 (Alpha)
**Focus:** Building Core Widgets based on "Blogs.pdf" Design System.

---

## 🧩 Component Roadmap

| ID | Component Name | Status | Notes |
| :--- | :--- | :--- | :--- |
| **01** | `cora_blog_hero` | ✅ **Done** | Dynamic Query, Overlay, Responsive Controls, Meta Logic. |
| **02** | `cora_post_grid` | 🔄 **Next** | The 3-column grid below the hero. |
| **03** | `cora_tech_strip` | 🔴 Pending | "Browse by Category" with logos. |
| **04** | `cora_solution_card` | 🔴 Pending | Complex card with internal list ("Struggling with..."). |
| **05** | `cora_industry_grid` | 🔴 Pending | "Insights for Every Industry" (Icon + Text). |
| **06** | `cora_team_carousel` | 🔴 Pending | "Meet the Minds" author slider. |
| **07** | `cora_cta_stats` | 🔴 Pending | Footer CTA with stat numbers. |

---

## 📝 Changelog

### [v1.0.0] - Blog Hero
- **Architecture:** Established `core/plugin_loader` and `Base_Widget`.
- [cite_start]**System:** Added `globals.css` for Gray Design System[cite: 1].
- **New Widget:** `cora_blog_hero`
    - Added "Latest Post" & "Manual ID" query sources.
    - Added "Read Time" auto-calculation.
    - [cite_start]Added fully responsive controls (Height, Padding, Radius) for Desktop/Tablet/Mobile[cite: 4, 5].
    - Added separate styling for Badge, Title, Meta, and Button.