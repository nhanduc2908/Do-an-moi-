import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../services/auth/mfa_service.dart';
import '../../widgets/common/custom_button.dart';
import '../../widgets/common/custom_textfield.dart';

class TwoFactorSetupScreen extends ConsumerStatefulWidget {
  const TwoFactorSetupScreen({super.key});

  @override
  ConsumerState<TwoFactorSetupScreen> createState() => _TwoFactorSetupScreenState();
}

class _TwoFactorSetupScreenState extends ConsumerState<TwoFactorSetupScreen> {
  final TextEditingController _codeController = TextEditingController();
  String _secret = '';
  bool _isEnabled = false;
  bool _isVerifying = false;

  @override
  void initState() {
    super.initState();
    _generateSecret();
  }

  void _generateSecret() {
    setState(() {
      _secret = 'ABCD EFGH IJKL MNOP';
    });
  }

  Future<void> _verifyAndEnable() async {
    if (_codeController.text.length != 6) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter a valid 6-digit code')),
      );
      return;
    }

    setState(() => _isVerifying = true);
    await Future.delayed(const Duration(seconds: 1));
    setState(() {
      _isVerifying = false;
      _isEnabled = true;
    });

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Two-factor authentication enabled')),
    );
    Navigator.pop(context);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Two-Factor Authentication'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    const Icon(Icons.qr_code, size: 100),
                    const SizedBox(height: 16),
                    const Text('Scan QR Code', style: TextStyle(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    const Text('Scan the QR code with your authenticator app'),
                    const SizedBox(height: 16),
                    Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: Colors.grey.shade100,
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(_secret, style: const TextStyle(fontSize: 18, letterSpacing: 2)),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    const Text('Verify Code', style: TextStyle(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    CustomTextField(
                      controller: _codeController,
                      label: '6-digit code',
                      prefixIcon: Icons.security,
                      keyboardType: TextInputType.number,
                    ),
                    const SizedBox(height: 16),
                    CustomButton(
                      text: 'Enable 2FA',
                      onPressed: _verifyAndEnable,
                      isLoading: _isVerifying,
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}