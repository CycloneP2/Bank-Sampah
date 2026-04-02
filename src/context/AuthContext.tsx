import { createContext, useContext, useState, useCallback } from 'react';
import type { User, UserRole, LoginCredentials } from '@/types';
import { MOCK_USERS } from '@/lib/mockData';

interface AuthContextType {
  user: User | null;
  role: UserRole | null;
  isAuthenticated: boolean;
  login: (credentials: LoginCredentials) => Promise<boolean>;
  logout: () => void;
  updateUser: (data: Partial<User>) => void;
  loginError: string | null;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(() => {
    const saved = localStorage.getItem('bank_sampah_user');
    return saved ? JSON.parse(saved) : null;
  });
  const [role, setRole] = useState<UserRole | null>(() => {
    const saved = localStorage.getItem('bank_sampah_user');
    return saved ? JSON.parse(saved).role : null;
  });
  const [loginError, setLoginError] = useState<string | null>(null);

  const login = useCallback(async (credentials: LoginCredentials): Promise<boolean> => {
    setLoginError(null);
    
    // Check for Demo Accounts first (for GitHub Pages / Static Demo)
    if (MOCK_USERS[credentials.email] && credentials.password === (credentials.email.split('@')[0] + '123')) {
      const userData = { ...MOCK_USERS[credentials.email], isDemo: true };
      setUser(userData);
      setRole(userData.role);
      localStorage.setItem('bank_sampah_user', JSON.stringify(userData));
      return true;
    }

    try {
      const response = await fetch('http://localhost/api/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(credentials)
      });
      
      const result = await response.json();
      
      if (response.ok && result.status === 'success') {
        const userData = result.data;
        // Fix data types if needed (MySQL can return numeric strings)
        userData.saldo = Number(userData.saldo);
        userData.totalSetoran = Number(userData.totalSetoran);
        
        setUser(userData);
        setRole(userData.role);
        localStorage.setItem('bank_sampah_user', JSON.stringify(userData));
        return true;
      } else {
        setLoginError(result.message || 'Email atau password salah. Silakan coba lagi.');
        return false;
      }
    } catch (error) {
      console.error('Login Error:', error);
      // Fallback message for failed backend connection
      setLoginError('Koneksi ke server backend (XAMPP) gagal. Pastikan Apache di XAMPP sudah menyala. Gunakan akun demo (pengurus@banksampah.com / pengurus123) jika Anda hanya ingin mencoba fitur.');
      return false;
    }
  }, []);

  const updateUser = useCallback((data: Partial<User>) => {
    setUser(prev => {
      if (!prev) return null;
      const updated = { ...prev, ...data };
      localStorage.setItem('bank_sampah_user', JSON.stringify(updated));
      return updated;
    });
  }, []);

  const logout = useCallback(() => {
    setUser(null);
    setRole(null);
    setLoginError(null);
    localStorage.removeItem('bank_sampah_user');
  }, []);

  return (
    <AuthContext.Provider
      value={{
        user,
        role,
        isAuthenticated: !!user,
        login,
        logout,
        updateUser,
        loginError,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};

