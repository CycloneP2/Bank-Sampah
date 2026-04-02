import { useState } from 'react';
import LandingPage from './sections/LandingPage';
import LoginPage from './sections/LoginPage';
import RegisterPage from './sections/RegisterPage';
import Dashboard from './sections/Dashboard';
import { AuthProvider } from './context/AuthContext';
import './App.css';

type AppPage = 'landing' | 'login' | 'register' | 'dashboard';

function App() {
  const [currentPage, setCurrentPage] = useState<AppPage>('landing');

  // Handle navigation
  const handleNavigate = (page: string) => {
    setCurrentPage(page as AppPage);
    window.scrollTo(0, 0);
  };

  // Render current page
  const renderPage = () => {
    switch (currentPage) {
      case 'landing':
        return <LandingPage onNavigate={handleNavigate} />;
      case 'login':
        return <LoginPage onNavigate={handleNavigate} />;
      case 'register':
        return <RegisterPage onNavigate={handleNavigate} />;
      case 'dashboard':
        return <Dashboard onNavigate={handleNavigate} />;
      default:
        return <LandingPage onNavigate={handleNavigate} />;
    }
  };

  return (
    <AuthProvider>
      <div className="relative">
        {/* Grain Overlay */}
        <div className="grain-overlay" />

        {/* Main Content */}
        {renderPage()}
      </div>
    </AuthProvider>
  );
}

export default App;
