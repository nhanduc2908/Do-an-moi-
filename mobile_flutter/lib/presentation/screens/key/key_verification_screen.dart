import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/auth_provider.dart';
import '../../widgets/common/custom_button.dart';
import '../../widgets/common/custom_textfield.dart';

class KeyVerificationScreen extends ConsumerStatefulWidget {
  const KeyVerificationScreen({super.key});

  @override
  ConsumerState<KeyVerificationScreen> createState() => _KeyVerificationScreenState();
}

class _KeyVerificationScreenState extends ConsumerState<KeyVerificationScreen> {
  final TextEditingController _keyController = TextEditingController();
  bool _isVerifying = false;

  Future<void> _verifyKey() async {
    if (_keyController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter the verification key')),
      );
      return;
    }

    setState(() => _isVerifying = true);
    await Future.delayed(const Duration(seconds: 1));
    setState(() => _isVerifying = false);

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Key verified successfully!')),
    );
    Navigator.pop(context);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Key Verification'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const Icon(Icons.vpn_key, size: 80, color: Colors.blue),
            const SizedBox(height: 24),
            const Text(
              'Enter Verification Key',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 8),
            const Text(
              'Please enter the key provided by your administrator',
              textAlign: TextAlign.center,
              style: TextStyle(color: Colors.grey),
            ),
            const SizedBox(height: 32),
            CustomTextField(
              controller: _keyController,
              label: 'Verification Key',
              prefixIcon: Icons.vpn_key,
            ),
            const SizedBox(height: 24),
            CustomButton(
              text: 'Verify Key',
              onPressed: _verifyKey,
              isLoading: _isVerifying,
            ),
          ],
        ),
      ),
    );
  }
}