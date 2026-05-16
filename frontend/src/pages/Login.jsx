import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

export default function Login() {
  const { login } = useAuth();
  const navigate = useNavigate();
  const [form, setForm] = useState({ email: '', password: '' });
  const [error, setError] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const user = await login(form.email, form.password);
      navigate(user.role === 'admin' ? '/admin' : '/');
    } catch {
      setError('Invalid email or password');
    }
  };

  return (
    <div className="min-h-[70vh] flex items-center justify-center px-4">
      <div className="card p-8 w-full max-w-md">
        <h1 className="text-2xl font-bold mb-6 text-center">Login to ShopHub</h1>
        {error && <p className="text-red-500 text-sm mb-4 text-center">{error}</p>}
        <form onSubmit={handleSubmit} className="space-y-4">
          <input className="input" type="email" placeholder="Email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} required />
          <input className="input" type="password" placeholder="Password" value={form.password} onChange={(e) => setForm({ ...form, password: e.target.value })} required />
          <button type="submit" className="btn-primary w-full">Login</button>
        </form>
        <p className="text-center text-sm mt-4 text-gray-500">
          Don't have an account? <Link to="/register" className="text-brand-600">Register</Link>
        </p>
        <p className="text-center text-xs mt-2 text-gray-400">Admin: admin@gmail.com / password</p>
      </div>
    </div>
  );
}
