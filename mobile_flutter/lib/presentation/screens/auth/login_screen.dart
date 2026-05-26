import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/routes/route_names.dart';
import '../../providers/auth_provider.dart';
import '../../providers/role_provider.dart';
import '../../widgets/common/custom_button.dart';
import '../../widgets/common/custom_textfield.dart';

class LoginScreen extends ConsumerStatefulWidget {
  const LoginScreen({super.key});

  @override
  ConsumerState<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends ConsumerState<LoginScreen> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isPasswordVisible = false;
  bool _isLoading = false;

  Future<void> _login() async {
    if (_emailController.text.isEmpty || _passwordController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter email and password')),
      );
      return;
    }

    setState(() => _isLoading = true);
    
    final success = await ref.read(authProvider.notifier).login(
      _emailController.text,
      _passwordController.text,
    );
    
    setState(() => _isLoading = false);
    
    if (success && mounted) {
      final role = ref.read(roleProvider);
      final route = _getDashboardRoute(role);
      context.go(route);
    }
  }

  String _getDashboardRoute(String? role) {
    switch (role) {
      case 'super_admin': return RouteNames.superAdminDashboard;
      case 'admin': return RouteNames.adminDashboard;
      case 'security_manager': return RouteNames.securityManagerDashboard;
      default: return RouteNames.viewerDashboard;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const Spacer(flex: 1),
              const Icon(Icons.security, size: 80, color: Colors.blue),
              const SizedBox(height: 20),
              Text(
                'Welcome Back',
                style: Theme.of(context).textTheme.headlineMedium,
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 8),
              Text(
                'Sign in to continue',
                style: Theme.of(context).textTheme.bodyMedium?.copyWith(color: Colors.grey),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 40),
              CustomTextField(
                controller: _emailController,
                label: 'Email',
                prefixIcon: Icons.email,
                keyboardType: TextInputType.emailAddress,
              ),
              const SizedBox(height: 16),
              CustomTextField(
                controller: _passwordController,
                label: 'Password',
                prefixIcon: Icons.lock,
                suffixIcon: IconButton(
                  icon: Icon(_isPasswordVisible ? Icons.visibility_off : Icons.visibility),
                  onPressed: () => setState(() => _isPasswordVisible = !_isPasswordVisible),
                ),
                obscureText: !_isPasswordVisible,
              ),
              const SizedBox(height: 8),
              Align(
                alignment: Alignment.centerRight,
                child: TextButton(
                  onPressed: () => context.go(RouteNames.forgotPassword),
                  child: const Text('Forgot Password?'),
                ),
              ),
              const SizedBox(height: 24),
              CustomButton(
                text: 'Login',
                onPressed: _login,
                isLoading: _isLoading,
              ),
              const SizedBox(height: 16),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Text("Don't have an account? "),
                  TextButton(
                    onPressed: () => context.go(RouteNames.register),
                    child: const Text('Register'),
                  ),
                ],
              ),
              const Spacer(flex: 2),
            ],
          ),
        ),
      ),
    );
  }
}