import { Link } from 'react-router-dom';
import { useState } from 'react';
import api from '../api/axios';

export default function Footer() {
  const [email, setEmail] = useState('');
  const [msg, setMsg] = useState('');

  const subscribe = async (e) => {
    e.preventDefault();
    try {
      await api.post('/newsletter', { email });
      setMsg('Subscribed successfully!');
      setEmail('');
    } catch {
      setMsg('Already subscribed or invalid email.');
    }
  };

  return (
    <footer className="bg-brand-900 text-white mt-16">
      <div className="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
          <h3 className="text-xl font-bold mb-4">ShopHub</h3>
          <p className="text-gray-300 text-sm">Your trusted online shopping destination in Bangladesh.</p>
        </div>
        <div>
          <h4 className="font-semibold mb-4">Quick Links</h4>
          <ul className="space-y-2 text-sm text-gray-300">
            <li><Link to="/products" className="hover:text-white">All Products</Link></li>
            <li><Link to="/about-us" className="hover:text-white">About Us</Link></li>
            <li><Link to="/contact" className="hover:text-white">Contact</Link></li>
            <li><Link to="/faq" className="hover:text-white">FAQ</Link></li>
          </ul>
        </div>
        <div>
          <h4 className="font-semibold mb-4">Policies</h4>
          <ul className="space-y-2 text-sm text-gray-300">
            <li><Link to="/privacy-policy" className="hover:text-white">Privacy Policy</Link></li>
            <li><Link to="/terms-conditions" className="hover:text-white">Terms & Conditions</Link></li>
          </ul>
        </div>
        <div>
          <h4 className="font-semibold mb-4">Newsletter</h4>
          <form onSubmit={subscribe} className="flex gap-2">
            <input
              type="email"
              placeholder="Your email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="flex-1 px-3 py-2 rounded-lg text-gray-900 text-sm"
              required
            />
            <button type="submit" className="bg-brand-500 px-4 py-2 rounded-lg text-sm font-medium hover:bg-brand-600">
              Join
            </button>
          </form>
          {msg && <p className="text-xs mt-2 text-green-300">{msg}</p>}
          <div className="flex gap-4 mt-4">
            {['Facebook', 'Instagram', 'Twitter'].map((s) => (
              <a key={s} href="#" className="text-gray-400 hover:text-white text-sm">{s}</a>
            ))}
          </div>
        </div>
      </div>
      <div className="border-t border-brand-700 py-4 text-center text-sm text-gray-400">
        © {new Date().getFullYear()} ShopHub. All rights reserved.
      </div>
    </footer>
  );
}

