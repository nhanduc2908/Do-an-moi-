import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/report_provider.dart';
import '../../widgets/common/custom_button.dart';

class GenerateReportScreen extends ConsumerStatefulWidget {
  const GenerateReportScreen({super.key});

  @override
  ConsumerState<GenerateReportScreen> createState() => _GenerateReportScreenState();
}

class _GenerateReportScreenState extends ConsumerState<GenerateReportScreen> {
  String _reportType = 'security_summary';
  String _format = 'PDF';
  bool _isGenerating = false;

  Future<void> _generateReport() async {
    setState(() => _isGenerating = true);
    
    await ref.read(reportProvider.notifier).generateReport({
      'type': _reportType,
      'format': _format.toLowerCase(),
    });
    
    setState(() => _isGenerating = false);
    
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Report generated successfully')),
      );
      Navigator.pop(context);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Generate Report'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            DropdownButtonFormField<String>(
              value: _reportType,
              items: const [
                DropdownMenuItem(value: 'security_summary', child: Text('Security Summary')),
                DropdownMenuItem(value: 'vulnerability_report', child: Text('Vulnerability Report')),
                DropdownMenuItem(value: 'compliance_report', child: Text('Compliance Report')),
                DropdownMenuItem(value: 'incident_report', child: Text('Incident Report')),
              ],
              onChanged: (value) => setState(() => _reportType = value!),
              decoration: const InputDecoration(
                labelText: 'Report Type',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 16),
            DropdownButtonFormField<String>(
              value: _format,
              items: const [
                DropdownMenuItem(value: 'PDF', child: Text('PDF')),
                DropdownMenuItem(value: 'Excel', child: Text('Excel')),
                DropdownMenuItem(value: 'CSV', child: Text('CSV')),
              ],
              onChanged: (value) => setState(() => _format = value!),
              decoration: const InputDecoration(
                labelText: 'Format',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 24),
            CustomButton(
              text: 'Generate Report',
              onPressed: _generateReport,
              isLoading: _isGenerating,
            ),
          ],
        ),
      ),
    );
  }
}