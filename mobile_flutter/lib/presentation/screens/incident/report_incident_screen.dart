import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/incident_provider.dart';
import '../../widgets/common/custom_button.dart';
import '../../widgets/common/custom_textfield.dart';

class ReportIncidentScreen extends ConsumerStatefulWidget {
  const ReportIncidentScreen({super.key});

  @override
  ConsumerState<ReportIncidentScreen> createState() => _ReportIncidentScreenState();
}

class _ReportIncidentScreenState extends ConsumerState<ReportIncidentScreen> {
  final _titleController = TextEditingController();
  final _descriptionController = TextEditingController();
  String _severity = 'medium';
  String _category = 'unauthorized_access';
  bool _isSubmitting = false;

  Future<void> _submitReport() async {
    if (_titleController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter incident title')),
      );
      return;
    }

    setState(() => _isSubmitting = true);
    
    final success = await ref.read(incidentProvider.notifier).createIncident({
      'title': _titleController.text,
      'description': _descriptionController.text,
      'severity': _severity,
      'category': _category,
    });
    
    setState(() => _isSubmitting = false);
    
    if (success && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Incident reported successfully')),
      );
      Navigator.pop(context);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Report Incident'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            CustomTextField(
              controller: _titleController,
              label: 'Incident Title',
              prefixIcon: Icons.title,
            ),
            const SizedBox(height: 16),
            CustomTextField(
              controller: _descriptionController,
              label: 'Description',
              prefixIcon: Icons.description,
              maxLines: 5,
            ),
            const SizedBox(height: 16),
            DropdownButtonFormField<String>(
              value: _severity,
              items: const [
                DropdownMenuItem(value: 'critical', child: Text('Critical - Major impact')),
                DropdownMenuItem(value: 'high', child: Text('High - Significant impact')),
                DropdownMenuItem(value: 'medium', child: Text('Medium - Moderate impact')),
                DropdownMenuItem(value: 'low', child: Text('Low - Minor impact')),
              ],
              onChanged: (value) => setState(() => _severity = value!),
              decoration: const InputDecoration(
                labelText: 'Severity',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 16),
            DropdownButtonFormField<String>(
              value: _category,
              items: const [
                DropdownMenuItem(value: 'unauthorized_access', child: Text('Unauthorized Access')),
                DropdownMenuItem(value: 'malware', child: Text('Malware Detection')),
                DropdownMenuItem(value: 'phishing', child: Text('Phishing Attack')),
                DropdownMenuItem(value: 'data_breach', child: Text('Data Breach')),
                DropdownMenuItem(value: 'dos', child: Text('Denial of Service')),
              ],
              onChanged: (value) => setState(() => _category = value!),
              decoration: const InputDecoration(
                labelText: 'Category',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 24),
            CustomButton(
              text: 'Report Incident',
              onPressed: _submitReport,
              isLoading: _isSubmitting,
            ),
          ],
        ),
      ),
    );
  }
}