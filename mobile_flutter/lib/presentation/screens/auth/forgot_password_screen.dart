import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/routes/route_names.dart';
import '../../providers/auth_provider.dart';
import '../../widgets/common/custom_button.dart';
import '../../widgets/common/custom_textfield.dart';

class ForgotPasswordScreen extends ConsumerStatefulWidget {
  const ForgotPasswordScreen({super.key});

  @override
  ConsumerState<ForgotPasswordScreen> createState() => _ForgotPasswordScreenState();
}

class _ForgotPasswordScreenState extends ConsumerState<ForgotPasswordScreen> {
  final _emailController = TextEditingController();
  bool _isLoading = false;
  bool _emailSent = false;

  Future<void> _sendResetLink() async {
    if (_emailController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter your email')),
      );
      return;
    }

    setState(() => _isLoading = true);
    
    await ref.read(authProvider.notifier).forgotPassword(_emailController.text);
    
    setState(() {
      _isLoading = false;
      _emailSent = true;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Forgot Password')),
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const SizedBox(height: 40),
            const Icon(Icons.lock_reset, size: 80, color: Colors.blue),
            const SizedBox(height: 20),
            Text(
              _emailSent ? 'Check Your Email' : 'Reset Password',
              style: Theme.of(context).textTheme.headlineSmall,
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 8),
            Text(
              _emailSent 
                ? 'We have sent a password reset link to your email address.'
                : 'Enter your email address and we will send you a link to reset your password.',
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(color: Colors.grey),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 40),
            if (!_emailSent) ...[
              CustomTextField(
                controller: _emailController,
                label: 'Email',
                prefixIcon: Icons.email,
                keyboardType: TextInputType.emailAddress,
              ),
              const SizedBox(height: 24),
              CustomButton(
                text: 'Send Reset Link',
                onPressed: _sendResetLink,
                isLoading: _isLoading,
              ),
            ],
            if (_emailSent) ...[
              CustomButton(
                text: 'Back to Login',
                onPressed: () => context.go(RouteNames.login),
              ),
            ],
            const SizedBox(height: 16),
            TextButton(
              onPressed: () => context.go(RouteNames.login),
              child: const Text('Back to Login'),
            ),
          ],
        ),
      ),
    );
  }
}