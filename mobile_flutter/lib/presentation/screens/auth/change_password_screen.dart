import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/routes/route_names.dart';
import '../../providers/auth_provider.dart';
import '../../widgets/common/custom_button.dart';
import '../../widgets/common/custom_textfield.dart';

class ChangePasswordScreen extends ConsumerStatefulWidget {
  const ChangePasswordScreen({super.key});

  @override
  ConsumerState<ChangePasswordScreen> createState() => _ChangePasswordScreenState();
}

class _ChangePasswordScreenState extends ConsumerState<ChangePasswordScreen> {
  final _currentController = TextEditingController();
  final _newController = TextEditingController();
  final _confirmController = TextEditingController();
  bool _isCurrentVisible = false;
  bool _isNewVisible = false;
  bool _isLoading = false;

  Future<void> _changePassword() async {
    if (_newController.text != _confirmController.text) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('New passwords do not match')),
      );
      return;
    }

    setState(() => _isLoading = true);
    
    final success = await ref.read(authProvider.notifier).changePassword(
      _currentController.text,
      _newController.text,
    );
    
    setState(() => _isLoading = false);
    
    if (success && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Password changed successfully')),
      );
      context.pop();
    }
  }

  @override
Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Change Password')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            const SizedBox(height: 20),
            CustomTextField(
              controller: _currentController,
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
              controller: _newController,
              label: 'New Password',
              prefixIcon: Icons.lock,
              obscureText: !_isNewVisible,
              suffixIcon: IconButton(
                icon: Icon(_isNewVisible ? Icons.visibility_off : Icons.visibility),
                onPressed: () => setState(() => _isNewVisible = !_isNewVisible),
              ),
            ),
            const SizedBox(height: 16),
            CustomTextField(
              controller: _confirmController,
              label: 'Confirm New Password',
              prefixIcon: Icons.lock_outline,
              obscureText: !_isNewVisible,
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