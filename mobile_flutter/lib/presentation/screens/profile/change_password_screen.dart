// Đường dẫn: mobile_flutter/lib/presentation/screens/profile/change_password_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/auth_provider.dart';
import '../../widgets/common/custom_button.dart';
import '../../widgets/common/custom_textfield.dart';

class ChangePasswordScreen extends ConsumerStatefulWidget {
  const ChangePasswordScreen({super.key});

  @override
  ConsumerState<ChangePasswordScreen> createState() => _ChangePasswordScreenState();
}

class _ChangePasswordScreenState extends ConsumerState<ChangePasswordScreen> {
  final TextEditingController _currentPasswordController = TextEditingController();
  final TextEditingController _newPasswordController = TextEditingController();
  final TextEditingController _confirmPasswordController = TextEditingController();
  
  bool _isCurrentVisible = false;
  bool _isNewVisible = false;
  bool _isConfirmVisible = false;
  bool _isLoading = false;

  String? _newPasswordError;
  String? _confirmPasswordError;

  void _validatePasswords() {
    final newPassword = _newPasswordController.text;
    final confirmPassword = _confirmPasswordController.text;
    
    setState(() {
      if (newPassword.length < 8) {
        _newPasswordError = 'Password must be at least 8 characters';
      } else if (!newPassword.contains(RegExp(r'[A-Z]'))) {
        _newPasswordError = 'Password must contain at least one uppercase letter';
      } else if (!newPassword.contains(RegExp(r'[a-z]'))) {
        _newPasswordError = 'Password must contain at least one lowercase letter';
      } else if (!newPassword.contains(RegExp(r'[0-9]'))) {
        _newPasswordError = 'Password must contain at least one number';
      } else if (!newPassword.contains(RegExp(r'[!@#$%^&*(),.?":{}|<>]'))) {
        _newPasswordError = 'Password must contain at least one special character';
      } else {
        _newPasswordError = null;
      }
      
      if (confirmPassword != newPassword) {
        _confirmPasswordError = 'Passwords do not match';
      } else {
        _confirmPasswordError = null;
      }
    });
  }

  Future<void> _changePassword() async {
    _validatePasswords();
    
    if (_newPasswordError != null || _confirmPasswordError != null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please fix the errors above')),
      );
      return;
    }
    
    if (_currentPasswordController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter your current password')),
      );
      return;
    }

    setState(() => _isLoading = true);
    
    final success = await ref.read(authProvider.notifier).changePassword(
      _currentPasswordController.text,
      _newPasswordController.text,
    );
    
    setState(() => _isLoading = false);
    
    if (success && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Password changed successfully')),
      );
      Navigator.pop(context);
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Current password is incorrect')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Change Password'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    const Icon(Icons.lock_reset, size: 48, color: Colors.blue),
                    const SizedBox(height: 16),
                    const Text(
                      'Change Your Password',
                      style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 8),
                    const Text(
                      'Choose a strong password that you don\'t use elsewhere',
                      textAlign: TextAlign.center,
                      style: TextStyle(color: Colors.grey),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),
            CustomTextField(
              controller: _currentPasswordController,
              label: 'Current Password',
              prefixIcon: Icons.lock_outline,
              obscureText: !_isCurrentVisible,
              suffixIcon: IconButton(
                icon: Icon(_isCurrentVisible ? Icons.visibility_off : Icons.visibility),
                onPressed: () => setState(() => _isCurrentVisible = !_isCurrentVisible),
              ),
            ),
            const SizedBox(height: 16),
            CustomTextField(
              controller: _newPasswordController,
              label: 'New Password',
              prefixIcon: Icons.lock,
              obscureText: !_isNewVisible,
              errorText: _newPasswordError,
              onChanged: (_) => _validatePasswords(),
              suffixIcon: IconButton(
                icon: Icon(_isNewVisible ? Icons.visibility_off : Icons.visibility),
                onPressed: () => setState(() => _isNewVisible = !_isNewVisible),
              ),
            ),
            const SizedBox(height: 16),
            CustomTextField(
              controller: _confirmPasswordController,
              label: 'Confirm New Password',
              prefixIcon: Icons.lock_outline,
              obscureText: !_isConfirmVisible,
              errorText: _confirmPasswordError,
              onChanged: (_) => _validatePasswords(),
              suffixIcon: IconButton(
                icon: Icon(_isConfirmVisible ? Icons.visibility_off : Icons.visibility),
                onPressed: () => setState(() => _isConfirmVisible = !_isConfirmVisible),
              ),
            ),
            const SizedBox(height: 16),
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.blue.shade50,
                borderRadius: BorderRadius.circular(8),
              ),
              child: const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Password Requirements:',
                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12),
                  ),
                  SizedBox(height: 4),
                  Text('• At least 8 characters', style: TextStyle(fontSize: 12)),
                  Text('• Uppercase and lowercase letters', style: TextStyle(fontSize: 12)),
                  Text('• At least one number', style: TextStyle(fontSize: 12)),
                  Text('• At least one special character', style: TextStyle(fontSize: 12)),
                ],
              ),
            ),
            const SizedBox(height: 24),
            CustomButton(
              text: 'Change Password',
              onPressed: _changePassword,
              isLoading: _isLoading,
            ),
          ],
        ),
      ),
    );
  }
}