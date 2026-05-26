import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/report_provider.dart';
import '../../widgets/common/custom_button.dart';

class ShareReportScreen extends ConsumerStatefulWidget {
  const ShareReportScreen({super.key, required this.reportId});
  final String reportId;

  @override
  ConsumerState<ShareReportScreen> createState() => _ShareReportScreenState();
}

class _ShareReportScreenState extends ConsumerState<ShareReportScreen> {
  final TextEditingController _emailController = TextEditingController();
  List<String> _recipients = [];
  bool _isSharing = false;

  void _addRecipient() {
    if (_emailController.text.isNotEmpty) {
      setState(() {
        _recipients.add(_emailController.text);
        _emailController.clear();
      });
    }
  }

  Future<void> _shareReport() async {
    if (_recipients.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please add at least one recipient')),
      );
      return;
    }

    setState(() => _isSharing = true);
    
    await ref.read(reportProvider.notifier).shareReport(widget.reportId, _recipients, null);
    
    setState(() => _isSharing = false);
    
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Report shared successfully')),
      );
      Navigator.pop(context);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Share Report'),
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
                    const Text('Add Recipients', style: TextStyle(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 16),
                    Row(
                      children: [
                        Expanded(
                          child: TextField(
                            controller: _emailController,
                            decoration: const InputDecoration(
                              hintText: 'Enter email address',
                              border: OutlineInputBorder(),
                            ),
                            keyboardType: TextInputType.emailAddress,
                          ),
                        ),
                        const SizedBox(width: 8),
                        IconButton(
                          icon: const Icon(Icons.add_circle, color: Colors.blue),
                          onPressed: _addRecipient,
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),
                    if (_recipients.isNotEmpty)
                      Wrap(
                        spacing: 8,
                        children: _recipients.map((email) => Chip(
                          label: Text(email),
                          onDeleted: () => setState(() => _recipients.remove(email)),
                        )).toList(),
                      ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 24),
            CustomButton(
              text: 'Share Report',
              onPressed: _shareReport,
              isLoading: _isSharing,
            ),
          ],
        ),
      ),
    );
  }
}