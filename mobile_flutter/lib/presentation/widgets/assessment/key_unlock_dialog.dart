// Đường dẫn: mobile_flutter/lib/presentation/widgets/assessment/key_unlock_dialog.dart

import 'package:flutter/material.dart';
import '../common/custom_button.dart';
import '../common/custom_textfield.dart';

class KeyUnlockDialog extends StatefulWidget {
  final String criteriaName;
  final Function(String) onUnlock;

  const KeyUnlockDialog({
    super.key,
    required this.criteriaName,
    required this.onUnlock,
  });

  @override
  State<KeyUnlockDialog> createState() => _KeyUnlockDialogState();
}

class _KeyUnlockDialogState extends State<KeyUnlockDialog> {
  final TextEditingController _keyController = TextEditingController();
  bool _isLoading = false;

  void _unlock() async {
    if (_keyController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter the verification key')),
      );
      return;
    }

    setState(() => _isLoading = true);
    await Future.delayed(const Duration(seconds: 1));
    setState(() => _isLoading = false);

    widget.onUnlock(_keyController.text);
    Navigator.pop(context);
  }

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      title: const Text('Unlock Criteria'),
      content: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text('Enter key to unlock: ${widget.criteriaName}'),
          const SizedBox(height: 16),
          CustomTextField(
            controller: _keyController,
            label: 'Verification Key',
            prefixIcon: Icons.vpn_key,
            obscureText: true,
          ),
        ],
      ),
      actions: [
        TextButton(
          onPressed: () => Navigator.pop(context),
          child: const Text('Cancel'),
        ),
        CustomButton(
          text: 'Unlock',
          onPressed: _unlock,
          isLoading: _isLoading,
          height: 36,
          width: 80,
        ),
      ],
    );
  }
}