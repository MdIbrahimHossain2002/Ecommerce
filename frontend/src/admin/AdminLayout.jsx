import { Link, Outlet, Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const nav = [
  { to: '/admin', label: 'Dashboard', end: true },
  { to: '/admin/products', label: 'Products' },
  { to: '/admin/orders', label: 'Orders' },
  { to: '/admin/customers', label: 'Customers' },
  { to: '/admin/categories', label: 'Categories' },
  { to: '/admin/coupons', label: 'Coupons' },
  { to: '/admin/reviews', label: 'Reviews' },
  { to: '/admin/banners', label: 'Banners' },
  { to: '/admin/settings', label: 'Settings' },
];

export default function AdminLayout() {
  const { user, isAdmin, loading } = useAuth();

  if (loading) return <div className="p-10">Loading...</div>;
  if (!user || !isAdmin) return <Navigate to="/login" />;

  return (
    <div className="min-h-screen flex bg-gray-100">
      <aside className="w-56 bg-brand-900 text-white shrink-0">
        <div className="p-4 font-bold text-lg border-b border-brand-700">ShopHub Admin</div>
        <nav className="p-2">
          {nav.map((item) => (
            <Link key={item.to} to={item.to} end={item.end} className="block px-3 py-2 rounded-lg text-sm hover:bg-brand-700 mb-1">
              {item.label}
            </Link>
          ))}
        </nav>
        <Link to="/" className="block px-4 py-3 text-sm text-brand-300 hover:text-white">← Back to Store</Link>
      </aside>
      <main className="flex-1 p-6 overflow-auto">
        <Outlet />
      </main>
    </div>
  );
}
