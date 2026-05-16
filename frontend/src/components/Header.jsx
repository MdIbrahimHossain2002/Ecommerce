import { Link, useNavigate } from 'react-router-dom';
import { useState } from 'react';
import { useAuth } from '../context/AuthContext';

export default function Header() {
  const { user, logout, isAdmin } = useAuth();
  const navigate = useNavigate();
  const [search, setSearch] = useState('');

  const handleSearch = (e) => {
    e.preventDefault();
    if (search.trim()) navigate(`/products?search=${encodeURIComponent(search.trim())}`);
  };

  return (
    <header className="bg-white border-b border-gray-200 sticky top-0 z-50">
      <div className="max-w-7xl mx-auto px-4">
        <div className="flex items-center justify-between h-16 gap-4">
          <Link to="/" className="text-2xl font-bold text-brand-600 shrink-0">
            Shop<span className="text-brand-900">Hub</span>
          </Link>

          <form onSubmit={handleSearch} className="flex-1 max-w-xl hidden md:flex">
            <input
              type="search"
              placeholder="Search products..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="input rounded-r-none"
            />
            <button type="submit" className="bg-brand-600 text-white px-5 rounded-r-lg hover:bg-brand-700">
              Search
            </button>
          </form>

          <nav className="flex items-center gap-4 shrink-0">
            <Link to="/products" className="text-sm font-medium text-gray-600 hover:text-brand-600 hidden sm:block">Shop</Link>
            <Link to="/wishlist" className="text-sm font-medium text-gray-600 hover:text-brand-600 hidden sm:block">Wishlist</Link>
            <Link to="/cart" className="text-sm font-medium text-gray-600 hover:text-brand-600">Cart</Link>
            {user ? (
              <>
                {isAdmin && (
                  <Link to="/admin" className="text-sm font-medium text-brand-600">Admin</Link>
                )}
                <Link to="/profile" className="text-sm font-medium text-gray-600 hover:text-brand-600">{user.name}</Link>
                <button onClick={logout} className="text-sm text-gray-500 hover:text-red-600">Logout</button>
              </>
            ) : (
              <Link to="/login" className="btn-primary text-sm py-2">Login</Link>
            )}
          </nav>
        </div>
      </div>
    </header>
  );
}

