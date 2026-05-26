import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/routes/route_names.dart';
import '../../providers/auth_provider.dart';
import '../../widgets/common/custom_button.dart';
import '../../widgets/common/custom_textfield.dart';

class RegisterScreen extends ConsumerStatefulWidget {
  const RegisterScreen({super.key});

  @override
  ConsumerState<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends ConsumerState<RegisterScreen> {
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmController = TextEditingController();
  bool _isPasswordVisible = false;
  bool _isLoading = false;

  Future<void> _register() async {
    if (_nameController.text.isEmpty || _emailController.text.isEmpty ||
        _passwordController.text.isEmpty || _confirmController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please fill all fields')),
      );
      return;
    }

    if (_passwordController.text != _confirmController.text) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Passwords do not match')),
      );
      return;
    }

    setState(() => _isLoading = true);
    
    final success = await ref.read(authProvider.notifier).register({
      'name': _nameController.text,
      'email': _emailController.text,
      'password': _passwordController.text,
      'password_confirmation': _confirmController.text,
    });
    
    setState(() => _isLoading = false);
    
    if (success && mounted) {
      context.go(RouteNames.login);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Register')),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            children: [
              const SizedBox(height: 20),
              CustomTextField(
                controller: _nameController,
                label: 'Full Name',
                prefixIcon: Icons.person,
              ),
              const SizedBox(height: 16),
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
                obscureText: !_isPasswordVisible,
                suffixIcon: IconButton(
                  icon: Icon(_isPasswordVisible ? Icons.visibility_off : Icons.visibility),
                  onPressed: () => setState(() => _isPasswordVisible = !_isPasswordVisible),
                ),
              ),
              const SizedBox(height: 16),
              CustomTextField(
                controller: _confirmController,
                label: 'Confirm Password',
                prefixIcon: Icons.lock_outline,
                obscureText: !_isPasswordVisible,
              ),
              const SizedBox(height: 24),
              CustomButton(
                text: 'Register',
                onPressed: _register,
                isLoading: _isLoading,
              ),
              const SizedBox(height: 16),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Text("Already have an account? "),
                  TextButton(
                    onPressed: () => context.go(RouteNames.login),
                    child: const Text('Login'),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}