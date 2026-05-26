import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/routes/route_names.dart';
import '../../providers/auth_provider.dart';
import '../../widgets/common/custom_button.dart';
import '../../widgets/common/custom_textfield.dart';

class TwoFactorScreen extends ConsumerStatefulWidget {
  const TwoFactorScreen({super.key});

  @override
  ConsumerState<TwoFactorScreen> createState() => _TwoFactorScreenState();
}

class _TwoFactorScreenState extends ConsumerState<TwoFactorScreen> {
  final _codeController = TextEditingController();
  bool _isLoading = false;

  Future<void> _verify() async {
    if (_codeController.text.length != 6) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter a valid 6-digit code')),
      );
      return;
    }

    setState(() => _isLoading = true);
    
    // Verify MFA code
    // await ref.read(authProvider.notifier).verifyMfa(_codeController.text);
    
    setState(() => _isLoading = false);
    context.go(RouteNames.home);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Two-Factor Authentication')),
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const SizedBox(height: 40),
            const Icon(Icons.security, size: 80, color: Colors.blue),
            const SizedBox(height: 20),
            Text(
              'Enter Verification Code',
              style: Theme.of(context).textTheme.headlineSmall,
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 8),
            Text(
              'Please enter the 6-digit code from your authenticator app',
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(color: Colors.grey),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 40),
            CustomTextField(
              controller: _codeController,
              label: 'Verification Code',
              prefixIcon: Icons.qr_code,
              keyboardType: TextInputType.number,
              maxLength: 6,
            ),
            const SizedBox(height: 24),
            CustomButton(
              text: 'Verify',
              onPressed: _verify,
              isLoading: _isLoading,
            ),
          ],
        ),
      ),
    );
  }
}