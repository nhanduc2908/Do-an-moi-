import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/auth_provider.dart';
import '../../widgets/common/custom_button.dart';
import '../../widgets/common/custom_textfield.dart';

class EditProfileScreen extends ConsumerStatefulWidget {
  const EditProfileScreen({super.key});

  @override
  ConsumerState<EditProfileScreen> createState() => _EditProfileScreenState();
}

class _EditProfileScreenState extends ConsumerState<EditProfileScreen> {
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _departmentController = TextEditingController();
  bool _isSaving = false;

  @override
  void initState() {
    super.initState();
    final user = ref.read(authProvider).user;
    _nameController.text = user?.name ?? '';
    _phoneController.text = user?.phone ?? '';
    _departmentController.text = user?.department ?? '';
  }

  Future<void> _saveProfile() async {
    setState(() => _isSaving = true);
    
    await ref.read(authProvider.notifier).updateProfile({
      'name': _nameController.text,
      'phone': _phoneController.text,
      'department': _departmentController.text,
    });
    
    setState(() => _isSaving = false);
    
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Profile updated successfully')),
      );
      Navigator.pop(context);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Edit Profile'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            CustomTextField(
              controller: _nameController,
              label: 'Full Name',
              prefixIcon: Icons.person,
            ),
            const SizedBox(height: 16),
            CustomTextField(
              controller: _phoneController,
              label: 'Phone Number',
              prefixIcon: Icons.phone,
              keyboardType: TextInputType.phone,
            ),
            const SizedBox(height: 16),
            CustomTextField(
              controller: _departmentController,
              label: 'Department',
              prefixIcon: Icons.business,
            ),
            const SizedBox(height: 24),
            CustomButton(
              text: 'Save Changes',
              onPressed: _saveProfile,
              isLoading: _isSaving,
            ),
          ],
        ),
      ),
    );
  }
}