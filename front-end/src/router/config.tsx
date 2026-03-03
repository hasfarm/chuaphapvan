
import { RouteObject } from "react-router-dom";
import { lazy } from "react";

const HomePage = lazy(() => import("../pages/home/page"));
const DashboardPage = lazy(() => import("../pages/dashboard/page"));
const FollowersPage = lazy(() => import("../pages/followers/page"));
const FollowerDetailPage = lazy(() => import("../pages/follower-detail/page"));
const FamiliesPage = lazy(() => import("../pages/families/page"));
const StarWorshipPage = lazy(() => import("../pages/star-worship/page"));
const WorshipCalendarPage = lazy(() => import("../pages/worship-calendar/page"));
const EventsPage = lazy(() => import("../pages/events/page"));
const MeritActivitiesPage = lazy(() => import("../pages/merit-activities/page"));
const FinancesPage = lazy(() => import("../pages/finances/page"));
const NotFoundPage = lazy(() => import("../pages/NotFound"));

const routes: RouteObject[] = [
  {
    path: "/",
    element: <DashboardPage />,
  },
  {
    path: "/home",
    element: <HomePage />,
  },
  {
    path: "/followers",
    element: <FollowersPage />,
  },
  {
    path: "/followers/:id",
    element: <FollowerDetailPage />,
  },
  {
    path: "/families",
    element: <FamiliesPage />,
  },
  {
    path: "/star-worship",
    element: <StarWorshipPage />,
  },
  {
    path: "/worship-calendar",
    element: <WorshipCalendarPage />,
  },
  {
    path: "/events",
    element: <EventsPage />,
  },
  {
    path: "/merit-activities",
    element: <MeritActivitiesPage />,
  },
  {
    path: "/finances",
    element: <FinancesPage />,
  },
  {
    path: "*",
    element: <NotFoundPage />,
  },
];

export default routes;
