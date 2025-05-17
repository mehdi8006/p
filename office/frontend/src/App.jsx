import { useState } from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import LoginPage from './pages/login';
import Maincontent from './maincontent';

export default function App() {
  const [user, setUser] = useState(null);

  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<LoginPage setUser={setUser} />} />
        {/* Use a wildcard so nested routes inside Maincontent work */}
        <Route path="/app/*" element={<Maincontent user={user} />} />
      </Routes>
    </BrowserRouter>
  );
}